<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;
use LaraZeus\Mark\Traits\Marker;
use Throwable;

/**
 * @mixin Model
 */
trait HasBookmarks
{
    use Marker;

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getBookmarkMorphPivotModel(), 'marker_id');
    }

    /**
     * @throws Throwable
     */
    public function hasBookmarked(Model $markable): bool
    {
        return $this->hasMarked('bookmarks', $markable);
    }

    /**
     * @throws Throwable
     */
    public function unmarkBookmark(Model $markable)
    {
        return $this->unmark('bookmarks', $markable);
    }

    /**
     * @throws Throwable
     */
    public function bookmark(Model $markable, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->mark('bookmarks', $markable, true, $metadata);
    }
}
