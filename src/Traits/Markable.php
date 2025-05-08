<?php

namespace LaraZeus\Mark\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LaraZeus\Mark\NotPassed;
use RuntimeException;
use Throwable;

trait Markable
{
    /**
     * @throws Throwable
     */
    public function markBy(
        string $relation,
        Model $marker,
        mixed $value = new NotPassed,
        array | null | NotPassed $metadata = new NotPassed
    ): Model {
        $attributes = [
            'marker_id' => $marker->getKey(),
        ];

        $values = [];

        if (! $value instanceof NotPassed) {
            $values['value'] = $value;
        }

        if (! $metadata instanceof NotPassed) {
            $values['metadata'] = $metadata;
        }

        return $this->getMarkRelation($relation)->updateOrCreate($attributes, $values);
    }

    /**
     * @throws Throwable
     */
    public function unmarkBy(string $relation, Model $marker)
    {
        return $this->getMarkRelation($relation)
            ->whereBelongsTo($marker, 'marker')
            ->first()
            ?->delete();
    }

    /**
     * @throws Throwable
     */
    public function hasMarkedBy(string $relation, Model $marker): bool
    {
        return $this->getMarkRelation($relation)->whereBelongsTo($marker, 'marker')->exists();
    }

    /**
     * @throws Throwable
     */
    protected function getMarkRelation(string $relationName): MorphMany
    {
        $relation = $this->{$relationName}();

        throw_unless(
            $relation instanceof MorphMany,
            new RuntimeException('mark relation "' . $relationName . '" must be instance of "' . MorphMany::class . '"')
        );

        throw_unless(
            in_array(Mark::class, class_uses_recursive($relation->getRelated()), true),
            new RuntimeException('mark relation "' . $relationName . '" must use trait "' . Mark::class . '"')
        );

        return $relation;
    }
}
