<?php

namespace LaraZeus\Mark\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use LaraZeus\Mark\Traits\Mark;

class MarkBookmark extends MorphPivot
{
    use Mark;
}
