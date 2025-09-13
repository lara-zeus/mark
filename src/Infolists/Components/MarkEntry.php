<?php

namespace LaraZeus\Mark\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Concerns\CanWrap;
use LaraZeus\Mark\Forms\Concerns\HasColors;
use LaraZeus\Mark\Infolists\Concerns\HasMarkRelations;
use LaraZeus\Mark\Forms\Concerns\HasSelectableIcons;

class MarkEntry extends Entry
{
    use CanWrap;
    use HasColors;
    use HasMarkRelations;
    use HasSelectableIcons;

    protected string $view = 'zeus-mark::infolists.components.mark-entry';
}
