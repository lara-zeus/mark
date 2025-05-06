<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait hasRatings
{
    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getRatingMorphPivotModel());
    }

    public function hasRated(Model $model): bool
    {
        return $this->ratings()->whereBelongsTo($model, 'markable')->exists();
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
            ->whereBelongsTo($markable, 'markable')
            ->first()
            ?->delete();
    }

    public function rate(Model $markable, int $value, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->markRating($markable, $value, $metaData);
    }
}
