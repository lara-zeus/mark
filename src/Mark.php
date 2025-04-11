<?php

namespace LaraZeus\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Arr;
use LaraZeus\Mark\Traits\Mark as MarkTrait;
use Throwable;

class Mark
{
    protected static ?string $markerModel = null;

    protected static string $likesMarkModel;

    protected static array $likesMarkablesModels;


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
     * @param  class-string<MorphPivot>  $markModel
     * @param  array<string, class-string<Model>>  $markablesModels
     */
    public function configureLikes(string $markModel, array $markablesModels): static
    {
        static::$likesMarkModel = $markModel;
        static::$likesMarkablesModels = $markablesModels;

        return $this;
    }

    /**
     * @return class-string<MorphPivot>
     */
    public function getLikesMarkModel(): string
    {
        return static::$likesMarkModel;
    }

    public function getLikeMarkables(): array
    {
        return static::$likesMarkablesModels;
    }
}
