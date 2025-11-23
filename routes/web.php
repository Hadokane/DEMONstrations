<?php

use App\Http\Controllers\TrackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\TrackController as AdminTrackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    //Dashboard Route
    Route::get('/dashboard', [TrackController::class, 'index'])->name('dashboard');
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    
    //Track Routes
    Route::get('/tracks/trending', [TrackController::class, 'trending'])->name('tracks.trending');
    Route::get('/tracks/upload', [TrackController::class, 'create'])->name('tracks.upload.form');
    Route::post('/tracks/upload', [TrackController::class, 'upload'])->name('tracks.upload');
    Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
    Route::patch('/tracks/{track}', [TrackController::class, 'update'])->name('tracks.update');
    Route::delete('/tracks/{track}', [TrackController::class, 'destroy'])->name('tracks.destroy');
    Route::get('/tracks/{track}/edit', [TrackController::class, 'edit'])->name('tracks.edit');
    Route::post('/tracks/{track}/play', [TrackController::class, 'recordPlay'])->name('tracks.play');
    Route::post('/tracks/{track}/reaction', [TrackController::class, 'addReaction'])->name('tracks.react');
    Route::post('/tracks/{track}/comment', [TrackController::class, 'addComment'])->name('tracks.comment');
    
    //Comment Routes
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::patch('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::delete('/admin/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('admin.comments.destroy');
        Route::delete('/admin/tracks/{track}', [AdminTrackController::class, 'destroy'])->name('admin.tracks.destroy');

    }); 

});

require __DIR__.'/auth.php';