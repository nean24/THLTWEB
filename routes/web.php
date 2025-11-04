<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'feed.index')->name('feed');
Route::view('/login', 'auth.login')->name('login');
Route::view('/profile', 'profile.index')->name('profile');
