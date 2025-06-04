<?php

namespace LaraZeus\Mark\Traits\Like\Markable;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Facades\Mark;

trait Relations
{
    public function likers()
    {
        return $this->morphToMany(
            related: Mark::getMarkerModel(),
            name: 'markable',
            table: (new (Mark::getLikeMorphPivotModel()))->getTable(),
            relatedPivotKey: 'marker_id'
        )
            ->using(Mark::getLikeMorphPivotModel())
            ->withPivot(['value', 'metadata'])
            ->withTimestamps();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Mark::getLikeMorphPivotModel(), 'markable');
    }

    public function like(): MorphOne
    {
        return $this->morphOne(Mark::getLikeMorphPivotModel(), 'markable');
    }
}
