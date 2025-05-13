<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait HasRatings
{
    public function ratings(): HasMany
    {
        return $this->hasMany(Mark::getRatingMorphPivotModel(), 'marker_id');
    }

    public function hasRated(Model $model): bool
    {
        return $this->ratings()->whereMorphedTo('markable', $model)->exists();
    }

    public function markRating(Model $markable, int $value, array | null | NotPassed $metaData = new NotPassed)
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

        return $this->ratings()->updateOrCreate($attributes, $values);
    }

    public function unmarkRating(Model $markable)
    {
        return $this->ratings()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function rate(Model $markable, int $value, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->markRating($markable, $value, $metaData);
    }
}
