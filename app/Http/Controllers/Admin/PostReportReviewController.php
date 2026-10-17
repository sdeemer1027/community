<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostReportReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reports = PostReport::with([
            'post.user',
            'reporter',
            'reviewer',
        ])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(20);

        return view('admin.post-reports.index', [
            'reports' => $reports,
        ]);
    }
}
