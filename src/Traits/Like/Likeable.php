<?php

namespace LaraZeus\Mark\Traits\Like;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait Likeable
{
    use Markable\Actions;
    use Markable\Indicators;
    use Markable\Relations;
    use Markable\Scopes;
}
