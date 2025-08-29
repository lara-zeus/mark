<?php

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Filament::auth()->login(User::first());

    return redirect()->to(Filament::getDefaultPanel()->getUrl());
})->name('home');

Route::redirect('/admin/login', '/');
