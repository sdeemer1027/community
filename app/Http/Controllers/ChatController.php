<?php

namespace App\Http\Controllers;

use App\Models\ChatBlock;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Connection;
use App\Models\ConnectionBlock;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List approved connections who are currently online
     * and available for chat.
     */
    public function onlineConnections(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $connectionIds = Connection::where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                    ->orWhere('recipient_id', $userId);
            })
            ->get()
            ->map(function ($connection) use ($userId) {
                return (int) (
                    $connection->requester_id === $userId
                        ? $connection->recipient_id
                        : $connection->requester_id
                );
            });

        $chatBlockedIds = ChatBlock::where(function ($query) use ($userId) {
            $query->where('blocker_id', $userId)
                ->orWhere('blocked_id', $userId);
        })->get()
            ->flatMap(fn ($block) => [
                (int) $block->blocker_id,
                (int) $block->blocked_id,
            ])
            ->reject(fn ($id) => $id === $userId);

        $connectionBlockedIds = ConnectionBlock::where(
            function ($query) use ($userId) {
                $query->where('blocker_id', $userId)
                    ->orWhere('blocked_id', $userId);
            }
        )->get()
            ->flatMap(fn ($block) => [
                (int) $block->blocker_id,
                (int) $block->blocked_id,
            ])
            ->reject(fn ($id) => $id === $userId);

        $excludedIds = $chatBlockedIds
            ->merge($connectionBlockedIds)
            ->unique()
            ->values();

        $users = User::whereIn('id', $connectionIds)
            ->whereNotIn('id', $excludedIds)
            ->where('id', '!=', $userId)
            ->where('last_seen_at', '>=', now()->subMinutes(2))
            ->orderBy('name')
            ->get(['id', 'name', 'profile_photo_path', 'last_seen_at']);

        return response()->json(['connections' => $users]);
    }

    /**
     * Open an existing conversation or create one.
     */
    public function openConversation(
        Request $request,
        User $user
    ): JsonResponse {
        $currentUserId = (int) $request->user()->id;
        $otherUserId = (int) $user->id;

        abort_if($currentUserId === $otherUserId, 422);

        $this->ensureChatAllowed($currentUserId, $otherUserId);

        $userOneId = min($currentUserId, $otherUserId);
        $userTwoId = max($currentUserId, $otherUserId);

        $conversation = ChatConversation::firstOrCreate(
            [
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
            ]
        );

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    /**
     * Load messages in a conversation.
     */
    public function messages(
        Request $request,
        ChatConversation $conversation
    ): JsonResponse {
        $userId = (int) $request->user()->id;

        abort_unless(
            in_array($userId, [
                (int) $conversation->user_one_id,
                (int) $conversation->user_two_id,
            ], true),
            403
        );

        $otherUserId = (int) (
            $conversation->user_one_id === $userId
                ? $conversation->user_two_id
                : $conversation->user_one_id
        );

        $this->ensureChatAllowed($userId, $otherUserId);

        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        $messages = $conversation->messages()
            ->with('sender:id,name')
            ->orderBy('id')
            ->limit(100)
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message.
     */
    public function sendMessage(
        Request $request,
        ChatConversation $conversation
    ): JsonResponse {
        $userId = (int) $request->user()->id;

        abort_unless(
            in_array($userId, [
                (int) $conversation->user_one_id,
                (int) $conversation->user_two_id,
            ], true),
            403
        );

        $otherUserId = (int) (
            $conversation->user_one_id === $userId
                ? $conversation->user_two_id
                : $conversation->user_one_id
        );

        $this->ensureChatAllowed($userId, $otherUserId);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $userId,
            'body' => $validated['body'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => $message->load('sender:id,name'),
        ], 201);
    }

    /**
     * Block chat without removing the connection.
     */
    public function blockChat(
        Request $request,
        User $user
    ): JsonResponse {
        $currentUserId = (int) $request->user()->id;
        $otherUserId = (int) $user->id;

        abort_if($currentUserId === $otherUserId, 422);

        $this->ensureAcceptedConnection($currentUserId, $otherUserId);

        ChatBlock::firstOrCreate([
            'blocker_id' => $currentUserId,
            'blocked_id' => $otherUserId,
        ]);

        return response()->json([
            'message' => 'Chat blocked.',
        ]);
    }

    /**
     * Block the connection separately from blocking chat.
     */
    public function blockConnection(
        Request $request,
        User $user
    ): JsonResponse {
        $currentUserId = (int) $request->user()->id;
        $otherUserId = (int) $user->id;

        abort_if($currentUserId === $otherUserId, 422);

        $this->ensureAcceptedConnection($currentUserId, $otherUserId);

        ConnectionBlock::firstOrCreate([
            'blocker_id' => $currentUserId,
            'blocked_id' => $otherUserId,
        ]);

        return response()->json([
            'message' => 'Connection blocked.',
        ]);
    }

  
    /**
     * Count unread messages received by the current user.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $conversations = ChatConversation::where(function ($query) use ($userId) {
            $query->where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId);
        })->get();

        $unreadCount = 0;

        foreach ($conversations as $conversation) {
            $otherUserId = (int) (
                $conversation->user_one_id === $userId
                    ? $conversation->user_two_id
                    : $conversation->user_one_id
            );

            $accepted = Connection::where('status', 'accepted')
                ->where(function ($query) use ($userId, $otherUserId) {
                    $query->where(function ($q) use ($userId, $otherUserId) {
                        $q->where('requester_id', $userId)
                            ->where('recipient_id', $otherUserId);
                    })->orWhere(function ($q) use ($userId, $otherUserId) {
                        $q->where('requester_id', $otherUserId)
                            ->where('recipient_id', $userId);
                    });
                })
                ->exists();

            if (! $accepted) {
                continue;
            }

            $blocked = ChatBlock::where(function ($query) use ($userId, $otherUserId) {
                $query->where('blocker_id', $userId)
                    ->where('blocked_id', $otherUserId);
            })->orWhere(function ($query) use ($userId, $otherUserId) {
                $query->where('blocker_id', $otherUserId)
                    ->where('blocked_id', $userId);
            })->exists();

            $connectionBlocked = ConnectionBlock::where(function ($query) use ($userId, $otherUserId) {
                $query->where('blocker_id', $userId)
                    ->where('blocked_id', $otherUserId);
            })->orWhere(function ($query) use ($userId, $otherUserId) {
                $query->where('blocker_id', $otherUserId)
                    ->where('blocked_id', $userId);
            })->exists();

            if ($blocked || $connectionBlocked) {
                continue;
            }

            $unreadCount += $conversation->messages()
                ->where('sender_id', '!=', $userId)
                ->whereNull('read_at')
                ->count();
        }

        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }



    /**
     * Verify that both users have an accepted connection.
     */
    private function ensureAcceptedConnection(
        int $userId,
        int $otherUserId
    ): void {
        $accepted = Connection::where('status', 'accepted')
            ->where(function ($query) use ($userId, $otherUserId) {
                $query->where(function ($q) use ($userId, $otherUserId) {
                    $q->where('requester_id', $userId)
                        ->where('recipient_id', $otherUserId);
                })->orWhere(function ($q) use ($userId, $otherUserId) {
                    $q->where('requester_id', $otherUserId)
                        ->where('recipient_id', $userId);
                });
            })
            ->exists();

        abort_unless($accepted, 403, 'An accepted connection is required.');
    }

    /**
     * Verify connection approval and both types of block.
     */
    private function ensureChatAllowed(
        int $userId,
        int $otherUserId
    ): void {
        $this->ensureAcceptedConnection($userId, $otherUserId);

        $chatBlocked = ChatBlock::where(function ($query) use ($userId, $otherUserId) {
            $query->where('blocker_id', $userId)
                ->where('blocked_id', $otherUserId);
        })->orWhere(function ($query) use ($userId, $otherUserId) {
            $query->where('blocker_id', $otherUserId)
                ->where('blocked_id', $userId);
        })->exists();

        abort_if($chatBlocked, 403, 'Chat is blocked.');

        $connectionBlocked = ConnectionBlock::where(function ($query) use ($userId, $otherUserId) {
            $query->where('blocker_id', $userId)
                ->where('blocked_id', $otherUserId);
        })->orWhere(function ($query) use ($userId, $otherUserId) {
            $query->where('blocker_id', $otherUserId)
                ->where('blocked_id', $userId);
        })->exists();

        abort_if($connectionBlocked, 403, 'This connection is blocked.');
    }
}
