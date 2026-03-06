<?php

namespace LaraZeus\Mark\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Concerns\CanWrap;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasMarkRelations;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class Mark extends Field
{
    use CanWrap;
    use HasColors;
    use HasMarkRelations;
    use HasSelectableIcons {
        HasSelectableIcons::like as likeIcons;
        HasSelectableIcons::rating as ratingIcons;
        HasSelectableIcons::bookmark as bookmarkIcons;
    }

    protected string $view = 'zeus-mark::forms.components.mark';

    public function like(): static
    {
        return $this
            ->likeIcons()
            ->rules(['boolean', 'nullable'])
            ->afterStateHydrated(static function ($component, $state): void {
                $component->state((string) $state);
            });
    }

    public function bookmark(): static
    {
        return $this
            ->bookmarkIcons()
            ->rule('boolean')
            ->afterStateHydrated(function ($component, $state) {
                $component->state((bool) $state);
            });
    }

    public function rating(): static
    {
        return $this
            ->ratingIcons()
            ->in(range(1, 5))
            ->nullable()
            ->afterStateHydrated(function ($component, $state) {
                $component->state((string) $state);
            });
    }
}
