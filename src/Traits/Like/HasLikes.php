<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark;
use LaraZeus\Mark\NotPassed;
use LaraZeus\Mark\Traits\Marker;
use Throwable;

/**
 * @mixin Model
 */
trait HasLikes
{
    use Marker;

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mark::getLikeMorphPivotModel(), 'marker_id');
    }

    /**
     * @throws Throwable
     */
    public function unmarkLike(Model $markable)
    {
        return $this->unmark('likes', $markable);
    }

    /**
     * @throws Throwable
     */
    public function like(Model $markable, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->mark('likes', $markable, true, $metadata);
    }

    /**
     * @throws Throwable
     */
    public function dislike(Model $markable, array | null | NotPassed $metadata = new NotPassed): Model
    {
        return $this->mark('likes', $markable, false, $metadata);
    }

    /**
     * @deprecated will be replaced with local scopes.
     */
    public function hasLiked(Model $model): bool
    {
        return $this->likes()->whereMorphedTo('markable', $model)->where('value', true)->exists();
    }

    /**
     * @throws Throwable
     *
     * @deprecated for better naming alternative, use like() instead.
     */
    public function markLike(Model $markable, bool $value, array | null | NotPassed $metaData = new NotPassed): Model
    {
        return $this->mark('likes', $markable, $value, $metaData);
    }
}
