<?php

namespace LaraZeus\Mark\Forms\Components;

use Filament\Forms\Components\Field;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasMarkRelations;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class Mark extends Field
{
    use HasColors;
    use HasMarkRelations;
    use HasSelectableIcons;

    protected string $view = 'zeus-mark::forms.components.mark';

    public function configureDefaults(): static
    {
        static::macro('rating', function () {
            return $this
                ->icons(array_fill(1, 5, 'heroicon-o-star'))
                ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
                ->sequential()
                ->in(array_keys($this->getIcons()));
        });

        static::macro('bookmark', function () {
            return $this
                ->icons('heroicon-o-bookmark')
                ->selectedIcons('heroicon-s-bookmark')
                ->in(array_keys($this->getIcons()));
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
                ])
                ->in(array_keys($this->getIcons()));
        });

        return $this;
    }
}
