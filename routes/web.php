<?php

use App\Http\Controllers\TrackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    //Dashboard Route
    Route::get('/dashboard', [TrackController::class, 'index'])->name('dashboard');
    
    // Track Routes
    Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
    Route::post('/tracks/{track}/play', [TrackController::class, 'recordPlay'])->name('tracks.play');
    Route::post('/tracks/{track}/reaction', [TrackController::class, 'addReaction'])->name('tracks.react');
    Route::post('/tracks/{track}/comment', [TrackController::class, 'addComment'])->name('tracks.comment');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';