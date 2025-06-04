<?php

namespace LaraZeus\Mark\Traits\Bookmark\Marker;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    public function unbookmark(Model $markable)
    {
        return $this->bookmarks()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function bookmark(Model $markable, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
        ];

        $values = [];

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
    }
}
