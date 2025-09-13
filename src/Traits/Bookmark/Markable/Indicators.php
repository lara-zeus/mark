<?php

namespace LaraZeus\Mark\Traits\Bookmark\Markable;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function isBookmarkedBy(Model $marker): bool
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->exists();
    }
}
