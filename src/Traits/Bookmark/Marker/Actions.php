<?php

namespace LaraZeus\Mark\Traits\Bookmark\Marker;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    public function markBookmark(Model $markable, bool $value, array | null | NotPassed $metaData = new NotPassed)
    {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metaData instanceof NotPassed) {
            $values['metadata'] = $metaData;
        }

        return $this->bookmarks()->updateOrCreate($attributes, $values);
    }

    public function unmarkBookmark(Model $markable)
    {
        return $this->bookmarks()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function bookmark(Model $markable, array | null | NotPassed $metaData = new NotPassed)
    {
        return $this->markBookmark($markable, true, $metaData);
    }
}
