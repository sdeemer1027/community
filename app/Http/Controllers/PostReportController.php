<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class PostReportController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => [
                'required',
                Rule::in([
                    'spam',
                    'harassment',
                    'inappropriate',
                    'false_information',
                    'other',
                ]),
            ],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($post->hidden_at) {
            return back()->with(
                'error',
                'This post is no longer available for reporting.'
            );
        }

        if ($post->user_id === $request->user()->id) {
            return back()->with(
                'error',
                'You cannot report your own post.'
            );
        }

        try {
            PostReport::create([
                'post_id' => $post->id,
                'reporter_id' => $request->user()->id,
                'reason' => $validated['reason'],
                'details' => $validated['details'] ?? null,
            ]);
        } catch (QueryException $exception) {
            if (
                $exception->getCode() === '23000' ||
                $exception->getCode() === '23505'
            ) {
                return back()->with(
                    'error',
                    'You have already reported this post.'
                );
            }

            throw $exception;
        }

        return back()->with(
            'success',
            'Thank you. Your report has been submitted for moderator review.'
        );
    }
}
