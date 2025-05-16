<?php

namespace LaraZeus\Mark\Traits\Rating;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait Rateable
{
    use Markable\Actions;
    use Markable\Indicators;
    use Markable\Relations;
    use Markable\Scopes;
}
