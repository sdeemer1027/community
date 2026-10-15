<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function index(Request $request): View
    {
        $requests = Connection::with('requester')
            ->where('recipient_id', $request->user()->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('connections.index', [
            'requests' => $requests,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $requesterId = $request->user()->id;
        $recipientId = (int) $validated['recipient_id'];

        if ($requesterId === $recipientId) {
            return back()->withErrors([
                'connection' => 'You cannot connect with yourself.',
            ]);
        }

        $alreadyExists = Connection::where(function ($query) use ($requesterId, $recipientId) {
            $query->where('requester_id', $requesterId)
                ->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($requesterId, $recipientId) {
            $query->where('requester_id', $recipientId)
                ->where('recipient_id', $requesterId);
        })->exists();

        if ($alreadyExists) {
            return back()->withErrors([
                'connection' => 'A connection request or connection already exists.',
            ]);
        }

        Connection::create([
            'requester_id' => $requesterId,
            'recipient_id' => $recipientId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Connection request sent.');
    }

    public function accept(Request $request, Connection $connection): RedirectResponse
    {
        if ($connection->recipient_id !== $request->user()->id) {
            abort(403);
        }




















        if ($connection->status !== 'pending') {
            return back()->withErrors([
                'connection' => 'This request has already been handled.',
            ]);
        }




        // Free Members may have at most 100 accepted connections.
        $user = $request->user();
        $requester = \App\Models\User::findOrFail($connection->requester_id);

        $membersToCheck = [$user, $requester];

        foreach ($membersToCheck as $member) {
            if ($member->membership_level !== 'free') {
                continue;
            }

            $acceptedCount = Connection::where('status', 'accepted')
                ->where(function ($query) use ($member) {
                    $query->where('requester_id', $member->id)
                        ->orWhere('recipient_id', $member->id);
                })
                ->count();


            $connectionLimit = config('community.free_member_connection_limit', 100);
            if ($acceptedCount >= $connectionLimit) {
                return back()->withErrors([
                    'connection' => 'A Free Member can have up to ' . $connectionLimit . ' accepted connections. Remove an existing connection before accepting another request.',
                ]);
            }
        }









        $connection->update([
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Connection accepted.');
    }























    public function decline(Request $request, Connection $connection): RedirectResponse
    {
        if ($connection->recipient_id !== $request->user()->id) {
            abort(403);
        }

        if ($connection->status !== 'pending') {
            return back()->withErrors([
                'connection' => 'This request has already been handled.',
            ]);
        }

        $connection->update([
            'status' => 'declined',
        ]);

        return back()->with('success', 'Connection request declined.');
    }
}
