<?php

namespace LaraZeus\Mark\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LaraZeus\Mark\Mark as MarkFacade;
use LaraZeus\Mark\Models\Traits\MarkConfigs;

class Mark extends MorphPivot
{
    use MarkConfigs;

    public $incrementing = true;

    protected $casts = [
        'metadata' => 'array',
    ];

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
