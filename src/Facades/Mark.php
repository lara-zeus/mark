<?php

namespace LaraZeus\Mark\Facades;

use Illuminate\Support\Facades\Facade;

class Mark extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LaraZeus\Mark\Mark::class;
    }
}
