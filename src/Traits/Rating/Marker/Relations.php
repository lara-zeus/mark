<?php

namespace LaraZeus\Mark\Traits\Rating\Marker;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function ratings(): HasMany
    {
        return $this->hasMany(Mark::getRatingMorphPivotModel(), 'marker_id');
    }
}
