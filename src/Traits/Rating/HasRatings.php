<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;
use LaraZeus\Mark\Traits\Marker;
use Throwable;

/**
 * @mixin Model
 */
trait HasRatings
{
    use Marker;

    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getRatingMorphPivotModel(), 'marker_id');
    }

    /**
     * @throws Throwable
     */
    public function hasRated(Model $markable): bool
    {
        return $this->hasMarked('ratings', $markable);
    }

    /**
     * @throws Throwable
     */
    public function unmarkRating(Model $markable)
    {
        return $this->unmark('ratings', $markable);
    }

    /**
     * @throws Throwable
     */
    public function rate(Model $markable, int $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->mark('ratings', $markable, $value, $metadata);
    }
}
