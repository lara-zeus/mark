<?php

namespace Larazeus\Mark\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Larazeus\Mark\Mark
 */
class Mark extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Larazeus\Mark\Mark::class;
    }
}
