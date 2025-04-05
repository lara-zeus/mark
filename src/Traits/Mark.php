<?php

namespace LaraZeus\Mark\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LaraZeus\Mark\Facades\Mark as MarkFacade;
use RuntimeException;

/**
 * @mixin Model
 */
trait Mark
{
    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    /**
     * @throws \Throwable
     */
    protected static function bootMark(): void
    {
        throw_unless(
            is_subclass_of(static::class, MorphPivot::class),
            new RuntimeException('"' . static::class . '" must be instance of "' . MorphPivot::class . '"')
        );
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function markable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<Model, $this>
     */
    public function marker(): BelongsTo
    {
        return $this->belongsTo(MarkFacade::getMarkerModel(), 'user_id');
    }
}
