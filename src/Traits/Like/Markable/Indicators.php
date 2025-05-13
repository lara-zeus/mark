<?php

namespace LaraZeus\Mark\Traits\Like\Markable;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function isLikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }

    public function isDislikedBy(Model $marker): bool
    {
        return $this->likes()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', false)
            ->exists();
    }
}
