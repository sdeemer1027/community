<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = $validated['search'] ?? '';
        $userId = $request->user()->id;

        $connections = Connection::whereIn('status', ['accepted', 'pending'])
            ->where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                    ->orWhere('recipient_id', $userId);
            })
            ->get();

        $connectedUserIds = $connections
            ->where('status', 'accepted')
            ->map(function ($connection) use ($userId) {
                return $connection->requester_id == $userId
                    ? $connection->recipient_id
                    : $connection->requester_id;
            });

        $pendingUserIds = $connections
            ->where('status', 'pending')
            ->map(function ($connection) use ($userId) {
                return $connection->requester_id == $userId
                    ? $connection->recipient_id
                    : $connection->requester_id;
            });

        $members = User::query()
            ->select('id', 'name', 'zip_code', 'profile_photo_path')
            ->where('id', '!=', $userId)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('zip_code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('members.index', [
            'members' => $members,
            'search' => $search,
            'connectedUserIds' => $connectedUserIds,
            'pendingUserIds' => $pendingUserIds,
        ]);
    }
}