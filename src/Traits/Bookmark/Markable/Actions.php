<?php

namespace LaraZeus\Mark\Traits\Bookmark\Markable;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    protected function markBookmark(Model $marker, bool $value, array | null | NotPassed $metaData = new NotPassed)
    {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metaData instanceof NotPassed) {
            $values['metadata'] = $metaData;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
    }

    public function unmarkBookmark(Model $marker)
    {
        return $this->bookmarks()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function bookmarkBy(Model $marker, $metadata = null): array
    {
        return $this->markBookmark($marker, true, $metadata);
    }
}
