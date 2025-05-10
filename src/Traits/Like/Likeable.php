<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    /**
     * @throws Throwable
     */
    public function hasLikedOrDislikedBy(Model $marker): bool
    {
        return $this->hasMarkedBy('likes', $marker);
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
}
