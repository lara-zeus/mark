<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Traits\Like\Markable\Actions;
use LaraZeus\Mark\Traits\Like\Markable\Indicators;
use LaraZeus\Mark\Traits\Like\Markable\Relations;
use LaraZeus\Mark\Traits\Like\Markable\Scopes;

/**
 * @mixin Model
 */
trait Likeable
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
