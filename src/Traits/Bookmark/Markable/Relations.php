<?php

namespace LaraZeus\Mark\Traits\Bookmark\Markable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function bookmarkers()
    {
        return $this->morphToMany(
            related: Mark::getMarkerModel(),
            name: 'markable',
            table: (new (Mark::getBookmarkMorphPivotModel()))->getTable(),
            relatedPivotKey: 'marker_id'
        )

            ->using(Mark::getBookmarkMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Mark::getBookmarkMorphPivotModel(), 'markable');
    }
}
