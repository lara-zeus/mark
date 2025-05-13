<?php

namespace LaraZeus\Mark\Traits\Rating\Markable;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function isRatedBy(Model $marker): bool
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->where('value', true)
            ->exists();
    }
}
