<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function likes(): HasMany
    {
        return $this->hasMany(Mark::getLikeMorphPivotModel(), 'marker_id');
    }
}
