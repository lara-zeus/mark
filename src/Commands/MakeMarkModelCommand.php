<?php

namespace LaraZeus\Mark\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;

class MakeMarkModelCommand extends ModelMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'mark:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mark model with its migration';

    public function handle()
    {
        $this->input->setOption('migration', true);
        $this->input->setOption('pivot', true);

        parent::handle();
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/mark.model.pivot.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return __DIR__ . '/../../stubs' . $stub;
    }

    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('mark:migration', [
            'name' => $table,
        ]);
    }
}
