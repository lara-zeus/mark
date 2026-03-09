<?php

namespace LaraZeus\Mark\Traits\Rating;

use LaraZeus\Mark\Traits\Rating\Marker\Actions;
use LaraZeus\Mark\Traits\Rating\Marker\Indicators;
use LaraZeus\Mark\Traits\Rating\Marker\Relations;
use LaraZeus\Mark\Traits\Rating\Marker\Scopes;
use Illuminate\Database\Eloquent\Model;

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
