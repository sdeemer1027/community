<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('id')
            ->get();

        return view('admin.roles.index', [
            'roles' => $roles,
        ]);
    }
}
