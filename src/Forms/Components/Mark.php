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

    public function like(): static
    {
        return $this
            ->icons(
                [
                    'heroicon-o-hand-thumb-down',
                    'heroicon-o-hand-thumb-up',
                ],
                [
                    'heroicon-s-hand-thumb-down',
                    'heroicon-s-hand-thumb-up',
                ]
            )
            ->rules(['boolean', 'nullable']);
    }

    public function bookmark(): static
    {
        return $this
            ->icons('heroicon-o-bookmark', 'heroicon-s-bookmark')
            ->rule('boolean');
    }

    public function rating(): static
    {
        return $this
            ->icons(
                array_fill(1, 5, 'heroicon-o-star'),
                array_fill(1, 5, 'heroicon-s-star')
            )
            ->sequential()
            ->in(range(1, 5))
            ->nullable();
    }
}
