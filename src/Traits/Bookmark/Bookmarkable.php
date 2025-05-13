<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait Bookmarkable
{
    use Markable\Actions;
    use Markable\Indicators;
    use Markable\Relations;
}
