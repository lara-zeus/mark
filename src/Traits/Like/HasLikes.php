<?php

namespace LaraZeus\Mark\Traits\Like;

use LaraZeus\Mark\Traits\Like\Marker\Actions;
use LaraZeus\Mark\Traits\Like\Marker\Indicators;
use LaraZeus\Mark\Traits\Like\Marker\Relations;
use LaraZeus\Mark\Traits\Like\Marker\Scopes;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasLikes
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
