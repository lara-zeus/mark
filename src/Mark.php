<?php

namespace LaraZeus\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Arr;
use LaraZeus\Mark\Traits\Mark as MarkTrait;

class Mark
{
    protected static ?string $markerModel = null;

    /**
     * @var array<class-string<Model>, array<class-string<MorphPivot>>>
     */
    protected static array $markablesWithMarks = [];

    /**
     * @return array<class-string<Model>, array<class-string<MorphPivot>>>
     */
    public function getMarkablesWithMarks(): array
    {
        return static::$markablesWithMarks;
    }

    public function getMarkersFromMarkableRelationName(string $markable, ?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        return (string) str($this->getMarksRelationName($mark))->append('By');
    }

    public function getMarkRelationName(?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        return (string) str($mark)->classBasename()->singular()->camel();
    }

    /**
     * @param  class-string<Model>|null  $mark
     */
    public function getMarksRelationName(?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        return (string) str($mark)->classBasename()->plural()->camel();
    }

    public function getMarkableFromMarkerRelationName(string $markable, ?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        return (string) str($this->getMarksRelationName($mark))->append(str($markable)->classBasename()->plural())->camel();
    }

    /**
     * @param  class-string<Model>  $markable
     * @param  class-string<MorphPivot>|array<class-string<MorphPivot>>  $marks
     * @return $this
     *
     * @throws \Throwable
     */
    public function addRelations(string $markable, string | array $marks): static
    {
        $marker = $this->getMarkerModel();
        $marks = Arr::wrap($marks);
        static::$markablesWithMarks[$markable] = $marks;

        /**
         * @var class-string<MorphPivot> $mark
         */
        foreach ($marks as $mark) {
            if (! in_array(MarkTrait::class, class_uses_recursive($mark))) {
                throw new \RuntimeException('Class "' . $mark . '" must use "' . Mark::class . '"');
            }

            $marker::resolveRelationUsing($this->getMarkableFromMarkerRelationName($markable, $mark), function (Model $marker) use ($mark, $markable) {
                return $marker->morphedByMany($markable, 'markable', (new $mark)->getTable())
                    ->using($mark)
                    ->withPivot(['value', 'metadata'])
                    ->withTimestamps();
            });

            $marker::resolveRelationUsing($this->getMarksRelationName($mark), function (Model $marker) use ($mark) {
                return $marker->hasMany($mark);
            });

            $marker::resolveRelationUsing($this->getMarkRelationName($mark), function (Model $marker) use ($mark) {
                return $marker->hasOne($mark);
            });

            $markable::resolveRelationUsing($this->getMarkersFromMarkableRelationName($markable, $mark), function (Model $markable) use ($mark) {
                return $markable->morphToMany($this->getMarkerModel(), 'markable', (new $mark)->getTable())
                    ->using($mark)
                    ->withPivot(['value', 'metadata', 'created_at', 'updated_at'])
                    ->withTimestamps();
            });

            $markable::resolveRelationUsing($this->getMarksRelationName($mark), function (Model $markable) use ($mark) {
                return $markable->morphMany($mark, 'markable');
            });

            $markable::resolveRelationUsing($this->getMarkRelationName($mark), function (Model $markable) use ($mark) {
                return $markable->morphOne($mark, 'markable');
            });
        }

        return $this;
    }

    public function markerModel(string $marker): static
    {
        static::$markerModel = $marker;

        return $this;
    }

    /**
     * @return class-string<Model>
     *
     * @throws \Throwable
     */
    public function getMarkerModel(): string
    {
        throw_if(! static::$markerModel, new \RuntimeException('Marker Model is not set.'));

        return static::$markerModel;
    }
}
