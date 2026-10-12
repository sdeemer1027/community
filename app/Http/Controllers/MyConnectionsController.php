<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
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
}
