<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MyConnectionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    


    // Local Traffic
    Route::get('/traffic', function () {
        $user = Auth::user();

        $zipLocation = null;

        if ($user && $user->zip_code) {
            $zipLocation = \Illuminate\Support\Facades\DB::table('zipcodes')
    ->where('zip', $user->zip_code)
    ->first(['zip', 'city', 'statename', 'lat', 'lng']);
        }

        return view('traffic.index', compact('zipLocation'));
    })->middleware('verified')->name('traffic.index');


   
    // Local Weather
    Route::get('/weather', function () {
        $user = Auth::user();

        $zipLocation = null;

        if ($user && $user->zip_code) {
            $zipLocation = \Illuminate\Support\Facades\DB::table('zipcodes')
                ->where('zip', $user->zip_code)
                ->first(['zip', 'city', 'statename', 'lat', 'lng']);
        }

        return view('weather.index', compact('zipLocation'));
    })->middleware('verified')->name('weather.index');



    // Local Public Safety
    Route::get('/safety', function () {
        $user = Auth::user();

        $zipLocation = null;

        if ($user && $user->zip_code) {
            $zipLocation = \Illuminate\Support\Facades\DB::table('zipcodes')
                ->where('zip', $user->zip_code)
                ->first(['zip', 'city', 'statename', 'lat', 'lng']);
        }

        return view('safety.index', compact('zipLocation'));
    })->middleware('verified')->name('safety.index');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Members
    Route::get('/members', [MemberController::class, 'index'])
        ->name('members.index');

    // Connection requests
    Route::get('/connections', [ConnectionController::class, 'index'])
        ->name('connections.index');
Route::patch(
    '/my-connections/{connection}/family',
    [MyConnectionsController::class, 'updateFamily']
)->name('my-connections.family');

Route::delete(
    '/my-connections/{connection}',
    [MyConnectionsController::class, 'destroy']
)->name('my-connections.destroy');

Route::delete(
    '/my-connections/chat-blocks/{chatBlock}',
    [MyConnectionsController::class, 'unblockChat']
)->name('my-connections.unblock-chat');

Route::delete(
    '/my-connections/connection-blocks/{connectionBlock}',
    [MyConnectionsController::class, 'unblockConnection']
)->name('my-connections.unblock-connection');

    Route::post('/connections', [ConnectionController::class, 'store'])
        ->name('connections.store');

    Route::patch('/connections/{connection}/accept', [ConnectionController::class, 'accept'])
        ->name('connections.accept');

    Route::patch('/connections/{connection}/decline', [ConnectionController::class, 'decline'])
        ->name('connections.decline');

    // Chat
            Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])
        ->name('chat.unread-count');
    Route::get('/chat/online', [ChatController::class, 'onlineConnections'])
        ->name('chat.online');

    Route::post('/chat/conversations/{user}', [ChatController::class, 'openConversation'])
        ->name('chat.conversations.open');

    Route::get('/chat/conversations/{conversation}/messages', [ChatController::class, 'messages'])
        ->name('chat.messages');

    Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])
        ->name('chat.messages.send');

    Route::post('/chat/users/{user}/block-chat', [ChatController::class, 'blockChat'])
        ->name('chat.block');

    Route::post('/chat/users/{user}/block-connection', [ChatController::class, 'blockConnection'])
        ->name('chat.block-connection');

    // Accepted connections
    Route::get('/my-connections', [MyConnectionsController::class, 'index'])
        ->name('my-connections.index');

    // Posts

        Route::get('/posts/feed', [PostController::class, 'feed'])
    ->middleware('verified')
    ->name('posts.feed');

    
   Route::post('/posts', [PostController::class, 'store'])
    ->middleware(['auth', 'permission:posts.create'])
    ->name('posts.store');

Route::get(
    '/posts/{post}/media/{mediaItem}',
    [PostController::class, 'media']
)->name('posts.media');

    // Private media library
    Route::get('/media-library', [MediaLibraryController::class, 'index'])
        ->name('media-library.index');


    Route::post('/media-library', [MediaLibraryController::class, 'store'])
        ->name('media-library.store');

    Route::delete('/media-library/{mediaItem}', [MediaLibraryController::class, 'destroy'])
        ->name('media-library.destroy');

Route::get('/media-library/{mediaItem}/file', [MediaLibraryController::class, 'file'])
    ->name('media-library.file');


});






//Admin Route
Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');
    
Route::get('/admin/members', [\App\Http\Controllers\Admin\MemberController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.members.index');

    Route::get('/admin/plugins', [\App\Http\Controllers\Admin\PluginController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.plugins.index');


Route::get('/admin/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'show'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.members.show');

Route::put('/admin/members/{member}/roles', [\App\Http\Controllers\Admin\MemberController::class, 'updateRoles'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.members.roles.update');

Route::get('/admin/roles', [RoleController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.roles.index');


Route::get('/moderation', [\App\Http\Controllers\ModerationController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:posts.moderate'])
    ->name('moderation.index');

Route::post('/moderation/posts/{post}/hide', [\App\Http\Controllers\ModerationController::class, 'hide'])
    ->middleware(['auth', 'verified', 'permission:posts.moderate'])
    ->name('moderation.posts.hide');

Route::post('/moderation/posts/{post}/restore', [\App\Http\Controllers\ModerationController::class, 'restore'])
    ->middleware(['auth', 'verified', 'permission:posts.moderate'])
    ->name('moderation.posts.restore');


Route::post('/posts/{post}/report', [\App\Http\Controllers\PostReportController::class, 'store'])
    ->middleware(['auth', 'verified', 'permission:reports.submit'])
    ->name('posts.report');


Route::get('/admin/post-reports', [\App\Http\Controllers\Admin\PostReportReviewController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:reports.review'])
    ->name('admin.post-reports.index');

Route::post('/admin/post-reports/{postReport}/review', [\App\Http\Controllers\Admin\PostReportReviewController::class, 'markReviewed'])
    ->middleware(['auth', 'verified', 'permission:reports.review'])
    ->name('admin.post-reports.review');


require __DIR__.'/auth.php';
