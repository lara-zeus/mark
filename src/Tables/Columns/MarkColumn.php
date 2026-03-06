<?php

namespace LaraZeus\Mark\Tables\Columns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\CanBeValidated;
use Filament\Tables\Columns\Concerns\CanUpdateState;
use Filament\Tables\Columns\Concerns\CanWrap;
use Filament\Tables\Columns\Contracts\Editable;
use Illuminate\Validation\Rule;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;
use LaraZeus\Mark\Tables\Concerns\HasMarkRelations;

class MarkColumn extends Column implements Editable
{
    use CanBeValidated;
    use CanUpdateState;
    use CanWrap;
    use HasColors;
    use HasMarkRelations;
    use HasSelectableIcons {
        HasSelectableIcons::like as protected likeIcons;
        HasSelectableIcons::rating as protected ratingIcons;
        HasSelectableIcons::bookmark as protected bookmarkIcons;
    }

    protected string $view = 'zeus-mark::tables.columns.mark-column';

    protected function setUp(): void
    {
        parent::setUp();

        $this->disabledClick();
    }

    public function like(): static
    {
        return $this
            ->likeIcons()
            ->rules(['boolean', 'nullable']);
    }

    public function bookmark(): static
    {
        return $this
            ->bookmarkIcons()
            ->rules(['boolean']);
    }

    public function rating(): static
    {
        return $this
            ->ratingIcons()
            ->rules([Rule::in(range(1, 5)), 'nullable']);
    }
}
