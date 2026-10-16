<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalMembers' => User::count(),
            'superAdmins' => User::where('is_super_admin', true)->count(),
            'freeMembers' => User::where('membership_level', 'free')->count(),
        ]);
    }
}
