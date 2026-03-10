<?php

namespace LaraZeus\Mark\Infolists\Components;

use Filament\Infolists\Components\Entry;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class MarkEntry extends Entry
{
    use HasColors;
    use HasSelectableIcons;

    protected string $view = 'lara-zeus-mark::infolists.components.mark-entry';

    protected bool $boolean = false;

    public function configureDefaults(): static
    {
        static::macro('rating', function () {
            return $this
                ->icons(array_fill(1, 5, 'heroicon-o-star'))
                ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
                ->sequential();
        });

        static::macro('like', function () {
            return $this
                ->icons([
                    'heroicon-o-hand-thumb-down',
                    'heroicon-o-hand-thumb-up',
                ])
                ->selectedIcons([
                    'heroicon-s-hand-thumb-down',
                    'heroicon-s-hand-thumb-up',
                ]);
        });

        static::macro('bookmark', function () {
            return $this
                ->icons([1 => 'heroicon-o-bookmark'])
                ->selectedIcons([1 => 'heroicon-s-bookmark'])
                ->boolean();
        });

        return $this;
    }

    public function boolean()
    {
        $this->boolean = true;

        return $this;
    }

    public function getBoolean(): bool
    {
        return $this->boolean;
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        return $this->getBoolean() ? (bool) $state : $state;
    }
}
