<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;

/**
 * @mixin Model
 */
trait hasLikes
{
    protected static function bootHasLikes(): void
    {
        foreach (Mark::getLikeMarkables() as $relationName => $markable) {
            $mark ??= Mark::getLikesMarkModel();

            static::resolveRelationUsing($relationName, function (Model $marker) use ($mark, $markable) {
                return $marker->morphedByMany($markable, 'markable', (new $mark)->getTable())
                    ->using($mark)
                    ->withPivot(['value', 'metadata'])
                    ->withTimestamps();
            });
        }
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getLikesMarkModel());
    }

    public function hasLiked(Model $model): bool
    {
        return $this->likes()->whereBelongsTo($model)->exists();
    }

    protected function markLike(Model $markable, $value, ?array $metaData = null)
    {
        return $this->likes()
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

    public function unmarkLike(Model $markable)
    {
        return $this->likes()
            ->whereBelongsTo($markable, 'markable')
            ->first()
            ?->delete();
    }

    public function like(Model $markable, ?array $metaData = null)
    {
        return $this->markLike($markable, true, $metaData);
    }

    public function dislike(Model $markable, ?array $metaData = null)
    {
        return $this->markLike($markable, false, $metaData);
    }
}
