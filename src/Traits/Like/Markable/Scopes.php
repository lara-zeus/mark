<?php

namespace LaraZeus\Mark\Traits\Like\Markable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeWhereLikedOrDislikedBy(Builder $query, Model|Collection $marker): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q->whereBelongsTo($marker, 'marker')
        );
    }

    public function scopeWhereLikedBy(Builder $query, Model|Collection $marker): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q
                ->whereBelongsTo($marker, 'marker')
                ->where('value', true)
        );
    }

    public function scopeWhereDislikedBy(Builder $query, Model|Collection $marker): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q
                ->whereBelongsTo($marker, 'marker')
                ->where('value', false)
        );
    }
}
