<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasRatings
{
    use Marker\Actions;
    use Marker\Indicators;
    use Marker\Relations;
}
