<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PluginController extends Controller
{
    public function index(): View
    {
        return view('admin.plugins.index');
    }
}
