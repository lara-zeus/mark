<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Traits\Rating\Marker\Actions;
use LaraZeus\Mark\Traits\Rating\Marker\Indicators;
use LaraZeus\Mark\Traits\Rating\Marker\Relations;
use LaraZeus\Mark\Traits\Rating\Marker\Scopes;

/**
 * @mixin Model
 */
trait HasRatings
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
