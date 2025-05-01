<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Concerns\EntanglesStateWithSingularRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use RuntimeException;
use Throwable;

trait HasMarkRelations
{
    use EntanglesStateWithSingularRelationship;

    /**
     * @param  array<string|int, mixed>|Closure(): array<string|int, mixed>|null  $metadata
     */
    public function relationship(?string $name = null, array | Closure | null $metadata = null): static
    {
        $name = $this->evaluate($name) ?? $this->getName();

        $this->loadStateFromRelationshipsUsing(function (Model $record, Field $component) use ($name) {
            $relation = $this->getMarkRelation($record, $name);
            $component->state(
                $relation->whereBelongsTo($this->getMarker(), 'marker')->value('value')
            );
        });

        $this->saveRelationshipsUsing(function (Model $record, $state) use ($name, $metadata) {
            $relation = $this->getMarkRelation($record, $name);

            if ($state === null) {
                $relation->whereBelongsTo($this->getMarker(), 'marker')->delete();

                return;
            }

            $values = ['value' => $state];

            if ($metadata !== null) {
                $values += ['metadata' => $this->evaluate($metadata)];
            }

            $relation->updateOrCreate(['marker_id' => $this->getMarker()->getKey()], $values);
        });

        $this->dehydrated(false);

        return $this;
    }

    /**
     * @return MorphOne<Model, Model>|MorphMany<Model, Model>
     *
     * @throws Throwable
     */
    protected function getMarkRelation(Model $record, string $name): MorphOne | MorphMany
    {
        $relation = $record->{$name}();

        if ($relation instanceof MorphOne || $relation instanceof MorphMany) {
            return $relation;
        }

        throw new RuntimeException('Relation "' . $name . '" must be instance of ("' . MorphOne::class . '" || "' . MorphOne::class . '").');
    }

    protected function getMarker(): Model
    {
        $user = auth()->user();

        if ($user instanceof Model) {
            return $user;
        }

        throw new RuntimeException('Authenticated User must be instance of "' . Model::class . '".');
    }
}
