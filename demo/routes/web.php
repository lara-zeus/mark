<?php

use App\Filament\Resources\CommentResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Filament::auth()->login(User::first());

    return redirect()->to(CommentResource::getUrl());
})->name('home');

Route::redirect('/admin/login', '/');
