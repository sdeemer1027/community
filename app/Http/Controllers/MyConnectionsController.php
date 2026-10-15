<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MyConnectionsController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $connections = Connection::with(['requester', 'recipient'])
            ->where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                    ->orWhere('recipient_id', $userId);
            })
            ->latest()
            ->get();

        return view('my-connections.index', [
            'connections' => $connections,
            'userId' => $userId,
        ]);
    }

    public function updateFamily(
        Request $request,
        Connection $connection
    ): RedirectResponse {
        $user = $request->user();

if ($user->membership_level === 'free') {
    return redirect()
        ->route('my-connections.index')
        ->withErrors([
            'is_family' => 'Family assignments are not available to Free Members.',
        ]);
}

        abort_unless(
            $connection->status === 'accepted'
            && in_array(
                (int) $user->id,
                [
                    (int) $connection->requester_id,
                    (int) $connection->recipient_id,
                ],
                true
            ),
            404
        );

        $validated = $request->validate([
            'is_family' => ['required', 'boolean'],
        ]);

        if ((int) $connection->requester_id === (int) $user->id) {
            $connection->requester_is_family = $validated['is_family'];
        } else {
            $connection->recipient_is_family = $validated['is_family'];
        }

        $connection->save();

        return redirect()
            ->route('my-connections.index')
            ->with('success', 'Family setting updated.');
    }



    public function destroy(
        Request $request,
        Connection $connection
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $connection->status === 'accepted'
            && in_array(
                (int) $user->id,
                [
                    (int) $connection->requester_id,
                    (int) $connection->recipient_id,
                ],
                true
            ),
            404
        );

        $connection->delete();

        return redirect()
            ->route('my-connections.index')
            ->with('success', 'Connection removed successfully.');
    }









}
