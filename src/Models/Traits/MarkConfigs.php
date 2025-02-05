<?php

namespace LaraZeus\Mark\Models\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaraZeus\Mark\Mark as MarkFacade;
use LaraZeus\Mark\Models\Mark;

trait MarkConfigs
{
    protected static ?string $markerModel = null;

    /**
     * @var array<class-string<Model>, array<Mark>>
     */
    protected static array $markablesWithMarks = [];

    /**
     * @var Closure(class-string<Mark>, class-string<Model>): string|null
     */
    protected static ?Closure $markerFromMarkableRelationNameUsing = null;

    /**
     * @var Closure(class-string<Mark>, class-string<Model>): string|null
     */
    protected static ?Closure $markableFromMarkerRelationNameUsing = null;

    /**
     * @var Closure(class-string<Mark>): string|null
     */
    protected static ?Closure $marksRelationNameUsing = null;

    /**
     * @var Closure(class-string<Mark>): string|null
     */
    protected static ?Closure $markRelationNameUsing = null;

    public function markerFromMarkableRelationNameUsing(?Closure $markerFromMarkableRelationNameUsing): static
    {
        self::$markerFromMarkableRelationNameUsing = $markerFromMarkableRelationNameUsing;

        return $this;
    }

    public function markableFromMarkerRelationNameUsing(?Closure $markableFromMarkerRelationNameUsing): static
    {
        self::$markableFromMarkerRelationNameUsing = $markableFromMarkerRelationNameUsing;

        return $this;
    }

    public function markRelationNameUsing(?Closure $markRelationNameUsing): static
    {
        self::$markRelationNameUsing = $markRelationNameUsing;

        return $this;
    }

    public function marksRelationNameUsing(?Closure $marksRelationNameUsing): static
    {
        self::$marksRelationNameUsing = $marksRelationNameUsing;

        return $this;
    }

    /**
     * @return array<class-string<Model>, array<Mark>>
     */
    public function getMarkablesWithMarks(): array
    {
        return static::$markablesWithMarks;
    }

    public function getMarkersFromMarkableRelationName(string $markable, ?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        if (static::$markerFromMarkableRelationNameUsing) {
            return (string) call_user_func(static::$markerFromMarkableRelationNameUsing, $markable, $mark);
        }

        return (string) str($this->getMarksRelationName($mark))->append('By');
    }

    public function getMarkRelationName(?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        if (static::$markRelationNameUsing) {
            return (string) call_user_func(static::$markRelationNameUsing, $mark);
        }

        return (string) str($mark)->classBasename()->singular()->camel();
    }

    public function getMarksRelationName(?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        if (static::$marksRelationNameUsing) {
            return (string) call_user_func(static::$marksRelationNameUsing, $mark);
        }

        return (string) str($mark)->classBasename()->plural()->camel();
    }

    public function getMarkableFromMarkerRelationName(string $markable, ?string $mark = null): string
    {
        $mark = $mark ?: static::class;

        if (static::$markableFromMarkerRelationNameUsing) {
            return (string) call_user_func(static::$markableFromMarkerRelationNameUsing, $markable, $mark);
        }

        return (string) str($this->getMarksRelationName($mark))->append(str($markable)->classBasename()->plural())->camel();
    }

    /**
     * @param  class-string<Model>  $markable
     * @param  class-string<Mark>|array<class-string<Mark>>  $marks
     * @return $this
     */
    public function addRelations(string $markable, string | array $marks): static
    {
        $marker = $this->getMarkerModel();
        $marks = Arr::wrap($marks);
        static::$markablesWithMarks[$markable] = $marks;

        foreach ($marks as $mark) {
            if (! is_subclass_of($mark, Mark::class)) {
                throw new \RuntimeException('Only marks that extends "' . Mark::class . '" but provided "' . $mark . '"');
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
                return $markable->morphToMany(MarkFacade::getMarkerModel(), 'markable', (new $mark)->getTable())
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
     */
    public function getMarkerModel(): string
    {
        return static::$markerModel ?? throw new \RuntimeException('Marker Model was not set, use "Mark::markerModel()" to set it.');
    }
}
