<?php

namespace LaraZeus\Mark\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\StateCasts\OptionStateCast;
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
                ->icons('heroicon-o-bookmark')
                ->selectedIcons('heroicon-s-bookmark')
                ->boolean(isNullable: false, isStoredAsInt: false);
        });

        return $this;
    }

    public function boolean(bool $isNullable = false, bool $isStoredAsInt = false): static
    {
        $this->boolean = true;

        return $this
            ->stateCast(
                app(BooleanStateCast::class, ['isNullable' => $isNullable, 'isStoredAsInt' => $isStoredAsInt])
            );
    }

    public function getBoolean(): bool
    {
        return $this->boolean;
    }


    public function getDefaultStateCasts(): array
    {
        if ($this->hasCustomStateCasts()) {
            return parent::getDefaultStateCasts();
        }

        return [app(OptionStateCast::class)];
    }
}
