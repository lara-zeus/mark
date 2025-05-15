<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasLikes
{
    use Marker\Actions;
    use Marker\Indicators;
    use Marker\Relations;
    use Marker\Scopes;
}
