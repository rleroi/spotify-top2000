<?php

use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', [PlaylistController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/global', [PlaylistController::class, 'global'])->middleware(['auth', 'verified'])->name('global');

Route::name('api.')
    ->prefix('/api')
    ->middleware(['auth', 'verified'])
    ->group(function (): void {
        Route::post('/insertTopTracks', [PlaylistController::class, 'insertTopTracks'])
            ->name('insertTopTracks');
        Route::post('/updateGlobalPlaylist', [PlaylistController::class, 'updateGlobalPlaylist'])
            ->name('updateGlobalPlaylist');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
