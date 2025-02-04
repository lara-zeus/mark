<?php

namespace Larazeus\Mark\Forms\Components;

use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;
use Larazeus\Mark\Facades\Mark as MarkFacade;
use Larazeus\Mark\Forms\Concerns\HasSelectableIcons;
use Larazeus\Mark\Mark as MarkModel;

class Mark extends Field
{
    use HasSelectableIcons;

    protected string $view = 'mark::forms.components.mark';

    public static function make(string $name): static
    {
        if (is_subclass_of($name, MarkModel::class)) {
            $name = MarkFacade::getMarkRelationName($name);
        }

        return parent::make($name)
            ->loadStateFromRelationshipsUsing(function (Model $record, Field $component) use ($name) {
                $component->state(
                    $record->{$name}()->where('user_id', Filament::auth()->id())->value('value')
                );
            })
            ->saveRelationshipsUsing(function (Model $record, $state) use ($name) {
                if ($state === null) {
                    $record->{$name}()->where('user_id', Filament::auth()->id())?->delete();

                    return;
                }
                $record->{$name}()->updateOrCreate(['user_id' => Filament::auth()->id()], ['value' => $state]);
            })
            ->dehydrated(false);
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
            ->isSequenceSelection()
            ->in(array_keys($this->getIcons()));
    }
}
