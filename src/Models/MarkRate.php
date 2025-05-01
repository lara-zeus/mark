<?php

namespace LaraZeus\Mark\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use LaraZeus\Mark\Traits\Mark;

class MarkRate extends MorphPivot
{
    use Mark;
}
