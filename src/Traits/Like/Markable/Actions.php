<?php

namespace LaraZeus\Mark\Traits\Like\Markable;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    protected function markLike(Model $marker, bool $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->likes()->updateOrCreate($attributes, $values);
    }

    public function unlikeBy(Model $marker)
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function likeBy(Model $marker, $metadata = null): Model
    {
        return $this->markLike($marker, true, $metadata);
    }

    public function dislikeBy(Model $marker, $metadata = null): Model
    {
        return $this->markLike($marker, false, $metadata);
    }
}
