
<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MyConnectionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaLibraryController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
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

    Route::post('/connections', [ConnectionController::class, 'store'])
        ->name('connections.store');

    Route::patch('/connections/{connection}/accept', [ConnectionController::class, 'accept'])
        ->name('connections.accept');

    Route::patch('/connections/{connection}/decline', [ConnectionController::class, 'decline'])
        ->name('connections.decline');

    // Accepted connections
    Route::get('/my-connections', [MyConnectionsController::class, 'index'])
        ->name('my-connections.index');

    // Posts
    Route::post('/posts', [PostController::class, 'store'])
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

require __DIR__.'/auth.php';
