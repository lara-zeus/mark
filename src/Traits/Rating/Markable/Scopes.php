<?php

namespace LaraZeus\Mark\Traits\Rating\Markable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeRatedBy(Builder $query, Model | Collection $marker): Builder
    {
        return $query->whereRelation(
            'ratings',
            fn (Builder $q) => $q->whereBelongsTo($marker, 'marker')
        );
    }
}
