<?php

namespace LaraZeus\Mark\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasMarkRelations;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class Mark extends Field
{
    use HasColors;
    use HasMarkRelations;
    use HasSelectableIcons;

    protected string $view = 'lara-zeus-mark::forms.components.mark';

    protected bool $boolean = false;

    public function configureDefaults(): static
    {
        static::macro('rating', function () {
            return $this
                ->icons(array_fill(1, 5, 'heroicon-o-star'))
                ->selectedIcons(array_fill(1, 5, 'heroicon-s-star'))
                ->sequential()
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

        static::macro('bookmark', function () {
            return $this
                ->icons([1 => 'heroicon-o-bookmark'])
                ->selectedIcons([1 => 'heroicon-s-bookmark'])
                ->boolean()
                ->rule('boolean');
        });

        return $this;
    }

    public function boolean(): static
    {
        $this->boolean = true;

        return $this
            ->stateCast(
                app(BooleanStateCast::class, ['isNullable' => false, 'isStoredAsInt' => false])
            );
    }

    public function getBoolean(): bool
    {
        return $this->boolean;
    }
}
