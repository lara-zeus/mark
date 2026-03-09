<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;
use LaraZeus\Mark\Traits\Bookmark\Marker\Actions;
use LaraZeus\Mark\Traits\Bookmark\Marker\Indicators;
use LaraZeus\Mark\Traits\Bookmark\Marker\Relations;
use LaraZeus\Mark\Traits\Bookmark\Marker\Scopes;

/**
 * @mixin Model
 */
trait HasBookmarks
{
    use Actions;
    use Indicators;
    use Relations;
    use Scopes;
}
