<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function hasLiked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->exists();
    }
}
