<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

/**
 * @mixin Model
 */
trait Likeable
{
    public function likedBy()
    {
        return $this->morphToMany(Mark::getMarkerModel(), 'markable', (new (Mark::getLikeMorphPivotModel()))->getTable())
            ->using(Mark::getLikeMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Mark::getLikeMorphPivotModel(), 'markable');
    }

    public function like(): MorphOne
    {
        return $this->morphOne(Mark::getLikeMorphPivotModel(), 'markable');
    }

    public function isLikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    public function isDislikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', false)
            ->exists();
    }

    protected function markLike(Model $marker, $value, ?array $metaData = null)
    {
        return $this->likes()
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

    public function unmarkLike(Model $marker)
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function likeBy(Model $marker, $metadata = null): array
    {
        return $this->markLike($marker, true, $metadata);
    }

    public function dislikeBy(Model $marker, $metadata = null): array
    {
        return $this->markLike($marker, false, $metadata);
    }
}
