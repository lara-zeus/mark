<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Traits\Bookmark\Markable\Actions;
use LaraZeus\Mark\Traits\Bookmark\Markable\Indicators;
use LaraZeus\Mark\Traits\Bookmark\Markable\Relations;
use LaraZeus\Mark\Traits\Bookmark\Markable\Scopes;

/**
 * @mixin Model
 */
trait Bookmarkable
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
