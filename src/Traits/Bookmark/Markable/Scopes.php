<?php

namespace LaraZeus\Mark\Traits\Bookmark\Markable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    public function scopeWhereBookmarkedBy(Builder $query, Model | Collection $marker): Builder
    {
        return $query->whereRelation(
            'bookmarks',
            fn (Builder $q) => $q->whereBelongsTo($marker, 'marker')
        );
    }
}
