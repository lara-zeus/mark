<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;

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

    public function markBookmark(Model $markable, $value, ?array $metaData = null)
    {
        return $this->bookmarks()
            ->updateOrCreate(
                [
                    'markable_type' => $markable->getMorphClass(),
                    'markable_id' => $markable->getKey(),
                ],
                [
                    'value' => $value,
                    'metadata' => $metaData,
                ]
            );
    }

    public function unmarkBookmark(Model $markable)
    {
        return $this->bookmarks()
            ->whereBelongsTo($markable, 'markable')
            ->first()
            ?->delete();
    }

    public function bookmark(Model $markable, ?array $metaData = null)
    {
        return $this->markBookmark($markable, true, $metaData);
    }
}
