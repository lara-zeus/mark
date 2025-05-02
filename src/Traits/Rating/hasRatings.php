<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;

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

    public function markRating(Model $markable, int $value, ?array $metaData = null)
    {
        return $this->ratings()
            ->updateOrCreate(
                [
                    'markable_type' => $markable->getMorphClass(),
                    'markable_id' => $markable->getKey(),
                ],
                [
                    'value' => (string) $value,
                    'metadata' => $metaData,
                ]
            );
    }

    public function unmarkRating(Model $markable)
    {
        return $this->ratings()
            ->whereBelongsTo($markable, 'markable')
            ->first()
            ?->delete();
    }

    public function rate(Model $markable, int $value, ?array $metaData = null)
    {
        return $this->markRating($markable, $value, $metaData);
    }
}
