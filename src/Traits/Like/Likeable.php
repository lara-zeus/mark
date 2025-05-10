<?php

namespace LaraZeus\Mark\Traits\Like;

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
trait Likeable
{
    use Markable;

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

    /**
     * @throws Throwable
     */
    public function unmarkLike(Model $marker)
    {
        return $this->unmarkBy('likes', $marker);
    }

    /**
     * @throws Throwable
     */
    public function likeBy(Model $marker, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->markBy('likes', $marker, true, $metadata);
    }

    /**
     * @throws Throwable
     */
    public function dislikeBy(Model $marker, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->markBy('likes', $marker, false, $metadata);
    }

    /**
     * @deprecated will be replaced with local scopes
     */
    public function isLikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    /**
     * @deprecated will be replaced with local scopes
     */
    public function isDislikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', false)
            ->exists();
    }

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, use likeBy(), dislikeBy() instead.
     */
    protected function markLike(Model $marker, bool $value, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->markBy('likes', $marker, $value, $metaData);
    }
}
