<?php

namespace LaraZeus\Mark\Traits\Bookmark\Marker;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function hasBookmarked(Model $model): bool
    {
        return $this->bookmarks()->whereMorphedTo('markable', $model)->exists();
    }
}
