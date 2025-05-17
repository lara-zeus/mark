<?php

namespace LaraZeus\Mark\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Tests\Factories\MarkableFactory;
use LaraZeus\Mark\Traits\Bookmark\Bookmarkable;
use LaraZeus\Mark\Traits\Like\Likeable;
use LaraZeus\Mark\Traits\Rating\Rateable;

class Markable extends Model
{
    use Bookmarkable;
    use HasFactory;
    use Likeable;
    use Rateable;

    protected static function newFactory(): MarkableFactory
    {
        return MarkableFactory::new();
    }
}
