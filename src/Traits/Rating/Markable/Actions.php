<?php

namespace LaraZeus\Mark\Traits\Rating\Markable;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    public function unrateBy(Model $marker)
    {
        return $this->ratings()
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    public function rateBy(Model $marker, int $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [
            'value' => $value,
        ];

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->ratings()->updateOrCreate($attributes, $values);
    }
}
