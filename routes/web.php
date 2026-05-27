<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/login', 'dashboard');
Route::view('/register', 'dashboard');
Route::view('/dashboard', 'dashboard');
Route::redirect('/app', '/dashboard');
Route::view('/docs', 'docs');
