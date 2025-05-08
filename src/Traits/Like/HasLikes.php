<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;

/**
 * @mixin Model
 */
trait HasLikes
{
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getLikeMorphPivotModel(), 'marker_id');
    }

    public function hasLiked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->exists();
    }

    public function markLike(Model $markable, bool $value, array | null | NotPassed $metaData = new NotPassed)
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

        return $this->likes()->updateOrCreate($attributes, $values);
    }

    public function unmarkLike(Model $markable)
    {
        return $this->likes()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function like(Model $markable, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->markLike($markable, true, $metaData);
    }

    public function dislike(Model $markable, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->unmarkLike($markable);
    }
}
