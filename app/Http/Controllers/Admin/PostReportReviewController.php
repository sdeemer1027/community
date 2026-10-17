<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostReport;
use Illuminate\Http\RedirectResponse;
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

    public function markReviewed(Request $request, PostReport $postReport): RedirectResponse
    {
        if ($postReport->status !== 'pending') {
            return back()->with(
                'success',
                'This report has already been reviewed.'
            );
        }

        $postReport->status = 'reviewed';
        $postReport->reviewed_by = $request->user()->id;
        $postReport->reviewed_at = now();
        $postReport->save();

        return back()->with(
            'success',
            'Report marked as reviewed.'
        );
    }
}
