<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $members = User::query()
            ->select('id', 'name', 'zip_code', 'profile_photo_path')
            ->where('id', '!=', $request->user()->id)
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
        ]);
    }
}