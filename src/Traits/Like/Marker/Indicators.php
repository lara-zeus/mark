<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function hasLikedOrDisliked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->exists();
    }

    public function hasLiked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->where('value', true)->exists();
    }

    public function hasDisliked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->where('value', false)->exists();
    }
}
