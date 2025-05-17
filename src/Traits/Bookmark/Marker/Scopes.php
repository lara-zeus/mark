<?php

namespace LaraZeus\Mark\Traits\Bookmark\Marker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeWhereBookmarked(Builder $query, Model | Collection $markable): Builder
    {
        return $query->whereRelation(
            'bookmarks',
            fn (Builder $q) => $q->whereMorphedTo('markable', $markable)
        );
    }
}
