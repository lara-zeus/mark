<?php

namespace LaraZeus\Mark\Traits\Like\Marker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeWhereLikedOrDisliked(Builder $query, Model|Collection $markable): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q->whereMorphedTo('markable', $markable)
        );
    }

    public function scopeWhereLiked(Builder $query, Model|Collection $markable): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q
                ->whereMorphedTo('markable', $markable)
                ->where('value', true)
        );
    }

    public function scopeWhereDisliked(Builder $query, Model|Collection $markable): Builder
    {
        return $query->whereRelation(
            'likes',
            fn (Builder $q) => $q
                ->whereMorphedTo('markable', $markable)
                ->where('value', false)
        );
    }
}
