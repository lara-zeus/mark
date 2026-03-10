<?php

namespace LaraZeus\Mark\Traits\Bookmark\Markable;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    public function unbookmarkBy(Model $marker)
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function bookmarkBy(Model $marker, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [];

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
    }
}
