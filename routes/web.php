<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FeedController::class, 'index'])->name('home');
Route::post('/posts', [FeedController::class, 'store'])->name('posts.store');

Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/posts/{id}/comments', [PostController::class, 'comment'])->name('posts.comment');

