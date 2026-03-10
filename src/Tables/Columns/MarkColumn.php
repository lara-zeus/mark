<?php

namespace LaraZeus\Mark\Tables\Columns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\CanBeValidated;
use Filament\Tables\Columns\Concerns\CanUpdateState;
use Filament\Tables\Columns\Contracts\Editable;
use Illuminate\Validation\Rule;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class MarkColumn extends Column implements Editable
{
    use CanBeValidated;
    use CanUpdateState;
    use HasColors;
    use HasSelectableIcons;

    protected string $view = 'lara-zeus-mark::tables.columns.mark-column';

    protected function setUp(): void
    {
        parent::setUp();

        $this->disabledClick();
    }

    public function configureDefaults(): static
    {
        static::macro('rating', function () {
            return $this
                ->icons(array_fill(1, 5, 'heroicon-o-star'))
                ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
                ->sequential()
                ->rules([Rule::in(array_keys($this->getIcons()))]);
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
                ->rules([Rule::in(array_keys($this->getIcons()))]);
        });

        static::macro('bookmark', function () {
            return $this
                ->icons([1 => 'heroicon-o-bookmark'])
                ->selectedIcons([1 => 'heroicon-s-bookmark'])
                ->rules(['boolean']);
        });

        return $this;
    }
}
