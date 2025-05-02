<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

/**
 * @mixin Model
 */
trait Rateable
{
    public function ratedBy()
    {
        return $this->morphToMany(Mark::getMarkerModel(), 'markable', (new (Mark::getRatingMorphPivotModel()))->getTable())
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

    public function isRatedBy(Model $marker): bool
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    protected function markRating(Model $marker, int $value, ?array $metaData = null)
    {
        return $this->ratings()
            ->updateOrCreate(
                [
                    'marker_id' => $marker->getKey(),
                ],
                [
                    'value' => (string) $value,
                    'metadata' => $metaData,
                ]
            );
    }

    public function unmarkRating(Model $marker)
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function rateBy(Model $marker, int $value, $metadata = null): array
    {
        return $this->markRating($marker, $value, $metadata);
    }
}
