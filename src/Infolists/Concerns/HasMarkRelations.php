<?php

namespace LaraZeus\Mark\Infolists\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\NotPassed;
use RuntimeException;
use Throwable;

trait HasMarkRelations
{
    /**
     * @param  array<string|int, mixed>|Closure|NotPassed|null  $metadata
     */
    public function relationship(
        ?string $name = null,
        array | Closure | NotPassed | null $metadata = new NotPassed,
        ?string $stateColumn = null
    ): static {
        $name = $this->evaluate($name) ?? $this->getName();

        return $this
            ->getStateUsing(function (Model $record) use ($name, $stateColumn) {
                $relation = $this->getMarkRelation($record, $name)
                    ->whereBelongsTo($this->getMarker(), 'marker');

                return is_null($stateColumn) ? $relation->exists() : $relation->value($stateColumn);
            });
    }

    /**
     * @return MorphOne<Model, Model>|MorphMany<Model, Model>
     *
     * @throws Throwable
     */
    protected function getMarkRelation(Model $record, string $name): MorphOne | MorphMany
    {
        $relation = $record->{$name}();

        throw_unless(
            $relation instanceof MorphOne || $relation instanceof MorphMany,
            new RuntimeException('Relation "' . $name . '" must be instance of ("' . MorphOne::class . '" || "' . MorphOne::class . '").')
        );

        return $relation;
    }

    /**
     * @throws Throwable
     */
    protected function getMarker(): Model
    {
        $marker = auth()->user();

        if ($marker instanceof Model) {
            return $marker;
        }

        throw new RuntimeException('Authenticated User must be instance of "' . Model::class . '".');
    }
}
