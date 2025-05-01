<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

/**
 * @mixin Model
 */
trait Bookmarkable
{
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

    public function bookmark(): MorphOne
    {
        return $this->morphOne(Mark::getBookmarkMorphPivotModel(), 'markable');
    }

    public function isBookmarkedBy(Model $marker): bool
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    protected function markBookmark(Model $marker, $value, ?array $metaData = null)
    {
        return $this->bookmarks()
            ->updateOrCreate(
                [
                    'marker_id' => $marker->getKey(),
                ],
                [
                    'value' => $value,
                    'metadata' => $metaData,
                ]
            );
    }

    public function unmarkBookmark(Model $marker)
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function bookmarkBy(Model $marker, $metadata = null): array
    {
        return $this->markBookmark($marker, true, $metadata);
    }
}
