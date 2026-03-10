<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaraZeus\Mark\Traits\Bookmark\Bookmarkable;
use LaraZeus\Mark\Traits\Like\Likeable;
use LaraZeus\Mark\Traits\Rating\Rateable;

class Comment extends Model
{
    use Bookmarkable;
    use Bookmarkable;

    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    use Likeable;
    use Rateable;

    protected $casts = [
      'text' => 'integer'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
