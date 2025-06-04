<?php

namespace LaraZeus\Mark\Traits\Rating\Markable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function raters()
    {
        return $this->morphToMany(
            related: Mark::getMarkerModel(),
            name: 'markable',
            table: (new (Mark::getRatingMorphPivotModel()))->getTable(),
            relatedPivotKey: 'marker_id'
        )
            ->using(Mark::getRatingMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Mark::getRatingMorphPivotModel(), 'markable');
    }
}
