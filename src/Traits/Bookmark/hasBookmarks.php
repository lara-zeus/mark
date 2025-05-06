<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait hasBookmarks
{
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getBookmarkMorphPivotModel());
    }

    public function hasBookmarked(Model $model): bool
    {
        return $this->bookmarks()->whereBelongsTo($model, 'markable')->exists();
    }

    public function markBookmark(Model $markable, bool $value, array | null | NotPassed $metaData = new NotPassed)
    {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metaData instanceof NotPassed) {
            $values['metadata'] = $metaData;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
    }

    public function unmarkBookmark(Model $markable)
    {
        return $this->bookmarks()
            ->whereBelongsTo($markable, 'markable')
            ->first()
            ?->delete();
    }

    public function bookmark(Model $markable, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->markBookmark($markable, true, $metaData);
    }
}
