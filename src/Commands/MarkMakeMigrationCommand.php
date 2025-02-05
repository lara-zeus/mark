<?php

namespace LaraZeus\Mark\Commands;

use Illuminate\Console\MigrationGeneratorCommand;

use function Laravel\Prompts\text;

class MarkMakeMigrationCommand extends MigrationGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:migration {name? : The name of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mark migration file';

    protected function migrationTableName()
    {
        return str($this->argument('name') ?? text('Name of the mark ?', required: true))
            ->lower();
    }

    protected function migrationStubFile()
    {
        return __DIR__ . '/../../stubs/mark.migration.create.stub';
    }
}
