<?php

namespace LaraZeus\Mark\Traits\Bookmark;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasBookmarks
{
    use Marker\Actions;
    use Marker\Indicators;
    use Marker\Relations;
    use Marker\Scopes;
}
