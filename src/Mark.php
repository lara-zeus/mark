<?php

namespace LaraZeus\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Throwable;

class Mark
{
    protected static ?string $markerModel = null;

    protected static string $likeMorphPivotModel;

    public function markerModel(string $marker): static
    {
        static::$markerModel = $marker;

        return $this;
    }

    /**
     * @return class-string<Model>
     *
     * @throws Throwable
     */
    public function getMarkerModel(): string
    {
        throw_if(! static::$markerModel, new \RuntimeException('Marker Model is not set.'));

        return static::$markerModel;
    }

    /**
     * @param  class-string<MorphPivot>  $model
     */
    public function likeMorphPivotModel(string $model): static
    {
        static::$likeMorphPivotModel = $model;

        return $this;
    }

    /**
     * @return class-string<MorphPivot>
     */
    public function getLikeMorphPivotModel(): string
    {
        return static::$likeMorphPivotModel;
    }
}
