<?php

namespace Larazeus\Mark;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Larazeus\Mark\Facades\Mark as MarkFacade;

class Mark extends MorphPivot
{
    use MarkConfigs;

    public $incrementing = true;

    protected $casts = [
        'metadata' => 'array',
    ];

    public function markable(): MorphTo
    {
        return $this->morphTo();
    }

    public function marker(): BelongsTo
    {
        return $this->belongsTo(MarkFacade::getMarkerModel(), 'user_id');
    }
}
