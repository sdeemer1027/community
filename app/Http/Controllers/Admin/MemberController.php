<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $members = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('zip_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'search' => $search,
        ]);
    }


    public function show(User $member): View
    {
    $roles = Role::orderBy('id')->get();

      return view('admin.members.show', [
          'member' => $member,
          'roles' => $roles,
       ]);
    }


public function updateRoles(Request $request, User $member): RedirectResponse
{
    if ($member->is_super_admin) {
        return redirect()
            ->route('admin.members.show', $member)
            ->with('error', 'Super Admin roles cannot be changed here.');
    }

    $validated = $request->validate([
        'role_ids' => ['nullable', 'array'],
        'role_ids.*' => ['integer', 'exists:roles,id'],
    ]);

    $roleIds = Role::query()
        ->whereIn('id', $validated['role_ids'] ?? [])
        ->where('name', '!=', 'super_admin')
        ->pluck('id')
        ->all();

    $member->roles()->sync($roleIds);

    return redirect()
        ->route('admin.members.show', $member)
        ->with('success', 'Member roles updated successfully.');
}



    
}
