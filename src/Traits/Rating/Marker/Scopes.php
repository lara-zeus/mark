<?php

namespace LaraZeus\Mark\Traits\Rating\Marker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeWhereRated(Builder $query, Model | Collection $markable): Builder
    {
        return $query->whereRelation(
            'ratings',
            fn (Builder $q) => $q->whereMorphedTo('markable', $markable)
        );
    }
}
