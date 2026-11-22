<?php

namespace LaraZeus\Mark\Tests\Fixtures;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use LaraZeus\Mark\Forms\Components\Mark;
use Livewire\Component;

class MarkFormComponent extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Mark::make('like')
                    ->like(),
                Mark::make('bookmark')
                    ->bookmark(),
                Mark::make('rating')
                    ->default(3)
                    ->colors([
                        1 => 'warning',
                        2 => 'warning',
                        3 => 'warning',
                        4 => 'warning',
                        5 => 'warning',
                    ])
                    ->rating(),
                Mark::make('disabled_rating')
                    ->rating()
                    ->disabled(),
                Mark::make('custom')
                    ->icons(fn (): array => [
                        'a' => 'heroicon-o-heart',
                        'b' => 'heroicon-o-fire',
                    ])
                    ->selectedIcons(fn (): array => [
                        'a' => 'heroicon-s-heart',
                        'b' => 'heroicon-s-fire',
                    ])
                    ->sequential(fn (): bool => false),
            ]);
    }

    public function save(): void
    {
        $this->form->getState();
    }

    public function render(): string
    {
        return <<<'HTML'
            <div>
                {{ $this->form }}
            </div>
        HTML;
    }
}
