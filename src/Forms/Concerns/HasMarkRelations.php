<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaraZeus\Mark\Forms\Components\Mark;
use LaraZeus\Mark\NotPassed;
use RuntimeException;
use Throwable;

trait HasMarkRelations
{
    /**
     * @param  array<string|int, mixed>|Closure|NotPassed|null  $metadata
     */
    public function relationship(?string $name = null, array | Closure | NotPassed | null $metadata = new NotPassed): static
    {
        $name = $this->evaluate($name) ?? $this->getName();

        return $this
            ->loadStateFromRelationshipsUsing(function (Model $record, Mark $component) use ($name) {
                $relation = $this->getMarkRelation($record, $name)
                    ->whereBelongsTo($this->getMarker(), 'marker');

                $component->state(
                    $component->isMultiple()
                        ? $relation->value('value')
                        : $relation->exists()
                );
            })
            ->saveRelationshipsUsing(function (Model $record, $state, Mark $component) use ($name, $metadata) {
                $relation = $this->getMarkRelation($record, $name);

                if (($component->isMultiple() && $state === null)
                    || (! $component->isMultiple() && $state === false)) {
                    $relation->whereBelongsTo($this->getMarker(), 'marker')->first()?->delete();

                    return;
                }

                $attributes = [
                    'marker_id' => $this->getMarker()->getKey(),
                ];

                if ($component->isMultiple()) {
                    $values = ['value' => $state];
                } else {
                    $values = [];
                }

                if (! $metadata instanceof NotPassed) {
                    $values += ['metadata' => $this->evaluate($metadata)];
                }

                $relation->updateOrCreate($attributes, $values);
            })
            ->dehydrated(false);
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
