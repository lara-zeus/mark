<?php

namespace LaraZeus\Mark\Forms\Components;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;
use LaraZeus\Mark\Mark as MarkFacade;
use LaraZeus\Mark\Models\Mark as MarkModel;

class Mark extends Field
{
    use HasSelectableIcons;

    protected string $view = 'zeus-mark::forms.components.mark';

    public function relationship(string | Closure | null $name = null): static
    {
        $name = $name === null ? $this->getName() : $this->evaluate($name);

        if (is_subclass_of($name, MarkModel::class)) {
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

    public function isLike(): static
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

    public function isBookMark(): static
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

    public function isRating(): static
    {
        return $this
            ->icons(array_fill(1, 5, 'heroicon-o-star'))
            ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
            ->sequential()
            ->in(array_keys($this->getIcons()));
    }
}
