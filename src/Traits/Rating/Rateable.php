<?php

namespace LaraZeus\Mark\Traits\Rating;

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
trait Rateable
{
    use Markable;

    public function ratedBy()
    {
        return $this->morphToMany(
            related: Mark::getMarkerModel(),
            name: 'markable',
            table: (new (Mark::getRatingMorphPivotModel()))->getTable(),
            relatedPivotKey: 'marker_id'
        )
            ->using(Mark::getRatingMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Mark::getRatingMorphPivotModel(), 'markable');
    }

    public function rating(): MorphOne
    {
        return $this->morphOne(Mark::getRatingMorphPivotModel(), 'markable');
    }

    /**
     * @throws Throwable
     */
    public function unmarkRating(Model $marker)
    {
        return $this->unmarkBy('ratings', $marker);
    }

    /**
     * @throws Throwable
     */
    public function rateBy(Model $marker, int $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->markBy('ratings', $marker, $value, $metadata);
    }

    /**
     * @deprecated will be replaced with local scopes
     */
    public function isRatedBy(Model $marker): bool
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, use rateBy() instead.
     */
    protected function markRating(Model $marker, int $value, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->markBy('ratings', $marker, $value, $metaData);
    }
}
