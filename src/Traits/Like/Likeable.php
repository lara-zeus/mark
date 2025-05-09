<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait Likeable
{
    public function likedBy()
    {
        return $this->morphToMany(
            related: Mark::getMarkerModel(),
            name: 'markable',
            table: (new (Mark::getLikeMorphPivotModel()))->getTable(),
            relatedPivotKey: 'marker_id'
        )
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

    protected function markLike(Model $marker, bool $value, array | null | NotPassed $metaData = new NotPassed)
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

        return $this->likes()->updateOrCreate($attributes, $values);
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
