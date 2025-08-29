<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaraZeus\Mark\Traits\Bookmark\Bookmarkable;
use LaraZeus\Mark\Traits\Like\Likeable;
use LaraZeus\Mark\Traits\Rating\Rateable;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, Likeable, Bookmarkable, Rateable;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
