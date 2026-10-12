<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MyConnectionsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/members', [MemberController::class, 'index'])
    ->name('members.index');

Route::get('/connections', [ConnectionController::class, 'index'])
    ->name('connections.index');

Route::patch('/connections/{connection}/accept', [ConnectionController::class, 'accept'])
    ->name('connections.accept');

Route::patch('/connections/{connection}/decline', [ConnectionController::class, 'decline'])
    ->name('connections.decline');

    Route::post('/connections', [ConnectionController::class, 'store'])
        ->name('connections.store');


Route::get('/my-connections', [MyConnectionsController::class, 'index'])
    ->name('my-connections.index');


});





require __DIR__.'/auth.php';