<?php

namespace LaraZeus\Mark\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaraZeus\Mark\NotPassed;
use RuntimeException;
use Throwable;

trait Marker
{
    /**
     * @throws Throwable
     */
    public function mark(
        string $relation,
        Model $markable,
        mixed $value = new NotPassed,
        array | null | NotPassed $metadata = new NotPassed
    ): Model {
        $attributes = [
            'markable_type' => $markable->getMorphClass(),
            'markable_id' => $markable->getKey(),
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
    public function unmark(string $relation, Model $markable)
    {
        return $this->getMarkRelation($relation)
            ->whereMorphedTo('markable', $markable)
            ->first()
            ?->delete();
    }

    /**
     * @throws Throwable
     */
    protected function getMarkRelation(string $relationName): HasMany
    {
        $relation = $this->{$relationName}();

        throw_unless(
            $relation instanceof HasMany,
            new RuntimeException('mark relation "' . $relationName . '" must be instance of "' . HasMany::class . '"')
        );

        throw_unless(
            in_array(Mark::class, class_uses_recursive($relation->getRelated()), true),
            new RuntimeException('mark relation "' . $relationName . '" must use trait "' . Mark::class . '"')
        );

        return $relation;
    }
}
