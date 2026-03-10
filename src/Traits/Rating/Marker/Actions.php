<?php

namespace LaraZeus\Mark\Traits\Rating\Marker;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\NotPassed;

trait Actions
{
    public function unrate(Model $markable)
    {
        return $this->ratings()
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    public function rate(Model $markable, int $value, array | null | NotPassed $metadata = new NotPassed): Model
    {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
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
