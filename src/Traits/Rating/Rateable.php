<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Traits\Rating\Markable\Actions;
use LaraZeus\Mark\Traits\Rating\Markable\Indicators;
use LaraZeus\Mark\Traits\Rating\Markable\Relations;
use LaraZeus\Mark\Traits\Rating\Markable\Scopes;

/**
 * @mixin Model
 */
trait Rateable
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
