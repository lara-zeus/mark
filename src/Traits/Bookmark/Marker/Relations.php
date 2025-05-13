<?php

namespace LaraZeus\Mark\Traits\Bookmark\Marker;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Mark::getBookmarkMorphPivotModel(), 'marker_id');
    }
}
