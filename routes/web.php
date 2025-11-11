<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'home.home')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/profile', 'profile.index')->name('profile');
Route::view('/post/{id}', 'post.show')->name('post.show');
Route::view('/posts/{id}', 'post.show')->name('posts.show');

