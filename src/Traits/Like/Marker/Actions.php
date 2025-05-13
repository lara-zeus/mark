<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
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
        return $this->markLike($markable, false, $metaData);
    }
}
