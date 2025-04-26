<?php

namespace LaraZeus\Mark\Forms\Components;

use Filament\Forms\Components\Field;
use LaraZeus\Mark\Forms\Concerns\HasMarkRelations;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class Mark extends Field
{
    use HasMarkRelations;
    use HasSelectableIcons;

    protected string $view = 'zeus-mark::forms.components.mark';

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

    public function bookmark(): static
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
