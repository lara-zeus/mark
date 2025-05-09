<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait Bookmarkable
{
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

    public function isBookmarkedBy(Model $marker): bool
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    protected function markBookmark(Model $marker, bool $value, array | null | NotPassed $metaData = new NotPassed)
    {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metaData instanceof NotPassed) {
            $values['metadata'] = $metaData;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
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
