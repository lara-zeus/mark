<?php

namespace LaraZeus\Mark\Forms\Components;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Concerns\EntanglesStateWithSingularRelationship;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Facades\Mark as MarkFacade;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;
use LaraZeus\Mark\Traits\Mark as MarkTrait;

class Mark extends Field
{
    use EntanglesStateWithSingularRelationship;
    use HasSelectableIcons;

    protected string $view = 'zeus-mark::forms.components.mark';

    public function relationship(string | Closure | null $name = null): static
    {
        $name = $this->evaluate($name) ?? $this->getName();

        if (class_exists($name) && in_array(MarkTrait::class, class_uses_recursive($name), true)) {
            $name = MarkFacade::getMarkRelationName($name);
        }

        $this->loadStateFromRelationshipsUsing(function (Model $record, Field $component) use ($name) {
            $component->state(
                $record->{$name}()->where('user_id', Filament::auth()->id())->value('value')
            );
        });

        $this->saveRelationshipsUsing(function (Model $record, $state) use ($name) {
            if ($state === null) {
                $record->{$name}()->where('user_id', Filament::auth()->id())->delete();

                return;
            }
            $record->{$name}()->updateOrCreate(['user_id' => Filament::auth()->id()], ['value' => $state]);
        });

        $this->dehydrated(false);

        return $this;
    }

    public function like(): static
    {
        return $this
            ->icons([
                true => 'heroicon-o-hand-thumb-up',
                false => 'heroicon-o-hand-thumb-down',
            ])
            ->selectedIcons([
                true => 'heroicon-s-hand-thumb-up',
                false => 'heroicon-s-hand-thumb-down',
            ])
            ->in(array_keys($this->getIcons()));
    }

    public function bookMark(): static
    {
        return $this
            ->icons([
                true => 'heroicon-o-bookmark',
            ])
            ->selectedIcons([
                true => 'heroicon-s-bookmark',
            ])
            ->in(array_keys($this->getIcons()));
    }

    public function rating(): static
    {
        return $this
            ->icons(array_fill(1, 5, 'heroicon-o-star'))
            ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
            ->sequential()
            ->in(array_keys($this->getIcons()));
    }
}
