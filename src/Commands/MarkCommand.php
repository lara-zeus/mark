<?php

namespace Larazeus\Mark\Commands;

use Illuminate\Console\Command;

class MarkCommand extends Command
{
    public $signature = 'mark';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
