<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Mark::getBookmarkMorphPivotModel(), 'marker_id');
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

    /**
     * @throws Throwable
     *
     * @deprecated will be replaced with local scopes
     */
    public function hasBookmarked(Model $markable): bool
    {
        return $this->bookmarks()->whereMorphedTo('markable', $markable)->exists();
    }

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, use bookmark() instead.
     */
    public function markBookmark(Model $markable, bool $value, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->mark('bookmarks', $markable, $value, $metaData);
    }
}
