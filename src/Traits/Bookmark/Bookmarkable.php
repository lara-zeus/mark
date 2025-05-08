<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;
use LaraZeus\Mark\Traits\Markable;
use Throwable;

/**
 * @mixin Model
 */
trait Bookmarkable
{
    use Markable;

    public function bookmarkedBy()
    {
        return $this->morphToMany(Mark::getMarkerModel(), 'markable', (new (Mark::getBookmarkMorphPivotModel()))->getTable())
            ->using(Mark::getBookmarkMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Mark::getBookmarkMorphPivotModel(), 'markable');
    }

    /**
     * @throws Throwable
     */
    public function hasBookmarkedBy(Model $marker): bool
    {
        return $this->hasMarkedBy('bookmarks', $marker);
    }

    /**
     * @throws Throwable
     */
    public function unmarkBookmark(Model $marker)
    {
        return $this->unmarkBy('bookmarks', $marker);
    }

    /**
     * @throws Throwable
     */
    public function bookmarkBy(Model $marker, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->markBy('bookmarks', $marker, true, $metadata);
    }
}
