<?php

namespace LaraZeus\Mark\Tests\Fixtures;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use LaraZeus\Mark\Forms\Components\Mark;
use LaraZeus\Mark\Tests\Models\Markable;
use Livewire\Component;

class MarkRelationshipFormComponent extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Markable $record;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(Markable $record): void
    {
        $this->record = $record;
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->record)
            ->statePath('data')
            ->components([
                Mark::make('likes')
                    ->like()
                    ->relationship('likes'),
                Mark::make('ratings')
                    ->rating()
                    ->relationship('ratings', metadata: ['source' => 'form']),
            ]);
    }

    public function save(): void
    {
        $this->form->getState();
        $this->form->saveRelationships();
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
