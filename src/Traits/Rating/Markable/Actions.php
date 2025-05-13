<?php

namespace LaraZeus\Mark\Traits\Rating\Markable;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    protected function markRating(Model $marker, int $value, array | null | NotPassed $metaData = new NotPassed)
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

        return $this->ratings()->updateOrCreate($attributes, $values);
    }

    public function unmarkRating(Model $marker)
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function rateBy(Model $marker, int $value, $metadata = null): array
    {
        return $this->markRating($marker, $value, $metadata);
    }
}
