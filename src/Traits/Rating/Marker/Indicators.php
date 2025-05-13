<?php

namespace LaraZeus\Mark\Traits\Rating\Marker;

use Illuminate\Database\Eloquent\Model;

trait Indicators
{
    public function hasRated(Model $model): bool
    {
        return $this->ratings()->whereMorphedTo('markable', $model)->exists();
    }
}
