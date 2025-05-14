<?php

namespace LaraZeus\Mark\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Tests\Factories\MarkerFactory;
use LaraZeus\Mark\Traits\Bookmark\HasBookmarks;
use LaraZeus\Mark\Traits\Like\HasLikes;
use LaraZeus\Mark\Traits\Rating\HasRatings;

class Marker extends Model
{
    use HasBookmarks;
    use HasFactory;
    use HasLikes;
    use HasRatings;

    protected static function newFactory(): MarkerFactory
    {
        return MarkerFactory::new();
    }
}
