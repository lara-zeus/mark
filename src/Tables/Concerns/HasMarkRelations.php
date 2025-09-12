<?php

namespace LaraZeus\Mark\Tables\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\NotPassed;
use LaraZeus\Mark\Tables\Columns\MarkColumn;
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
            ->getStateUsing(function (Model $record, MarkColumn $column) use ($name, $stateColumn) {
                $relation = $this->getMarkRelation($record, $name)
                    ->whereBelongsTo($this->getMarker(), 'marker');

                return is_null($stateColumn) ? $relation->exists() : $relation->value($stateColumn);
            })
            ->updateStateUsing(function (Model $record, $state) use ($name, $metadata, $stateColumn) {
                $relation = $this->getMarkRelation($record, $name);

                if ((is_null($stateColumn) && $state === false) || (! is_null($stateColumn) && $state === null)) {
                    $relation->whereBelongsTo($this->getMarker(), 'marker')->first()?->delete();

                    return;
                }

                $attributes = [
                    'marker_id' => $this->getMarker()->getKey(),
                ];

                $values = [];

                if (! is_null($stateColumn)) {
                    $values = ['value' => $state];
                }

                if (! $metadata instanceof NotPassed) {
                    $values += ['metadata' => $this->evaluate($metadata)];
                }

                $relation->updateOrCreate($attributes, $values);
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
