<?php

namespace LaraZeus\Mark\Traits\Rating;

use LaraZeus\Mark\Traits\Rating\Markable\Actions;
use LaraZeus\Mark\Traits\Rating\Markable\Indicators;
use LaraZeus\Mark\Traits\Rating\Markable\Relations;
use LaraZeus\Mark\Traits\Rating\Markable\Scopes;
use Illuminate\Database\Eloquent\Model;

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
