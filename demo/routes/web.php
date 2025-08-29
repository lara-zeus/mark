<?php

use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Models\Comment;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Filament::auth()->login(User::first());

    return redirect()->to(EditComment::getUrl([Comment::first()]));
})->name('home');

Route::redirect('/admin/login', '/');
