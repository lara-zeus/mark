<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    protected function markLike(Model $markable, bool $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->likes()->updateOrCreate($attributes, $values);
    }

    public function unlike(Model $markable)
    {
        return $this->likes()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function like(Model $markable, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->markLike($markable, true, $metaData);
    }

    public function dislike(Model $markable, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->markLike($markable, false, $metaData);
    }
}
