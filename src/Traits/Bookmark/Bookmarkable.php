<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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

    public function bookmark(): MorphOne
    {
        return $this->morphOne(Mark::getBookmarkMorphPivotModel(), 'markable');
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

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, Use hasBookmarkedBy() instead
     */
    public function isBookmarkedBy(Model $marker): bool
    {
        return $this->hasBookmarkedBy($marker);
    }

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, use bookmarkBy() instead.
     */
    protected function markBookmark(Model $marker, bool $value, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->markBy('bookmarks', $marker, $value, $metaData);
    }
}
