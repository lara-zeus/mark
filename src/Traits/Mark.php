<?php

namespace LaraZeus\Mark\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
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
    /**
     * @throws \Throwable
     */
    protected static function bootMark(): void
    {
        throw_unless(is_subclass_of(static::class, MorphPivot::class), new RuntimeException('"' . static::class . '" must be instance of "' . MorphPivot::class . '"'));
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

    public static function add(
        Model $markable,
        Authenticatable $user,
        ?string $value = null,
        array $metadata = []
    ): self {
        $attributes = [
            'user_id' => $user->getKey(),
            'markable_id' => $markable->getKey(),
            'markable_type' => $markable->getMorphClass(),
            'value' => $value,
        ];

        $values = collect([
            'metadata' => $metadata,
        ])->toArray();

        return static::firstOrCreate($attributes, $values);
    }

    public static function remove(Model $markable, Authenticatable $user, ?string $value = null)
    {
        return static::where([
            'user_id' => $user->getKey(),
            'markable_id' => $markable->getKey(),
            'markable_type' => $markable->getMorphClass(),
            'value' => $value,
        ])->get()->each->delete();
    }

    public static function count(Model $markable, ?string $value = null): int
    {
        return static::where([
            'markable_id' => $markable->getKey(),
            'markable_type' => $markable->getMorphClass(),
            'value' => $value,
        ])->count();
    }

    public static function has(Model $markable, Authenticatable $user, ?string $value = null): bool
    {
        return static::where([
            'user_id' => $user->getKey(),
            'markable_id' => $markable->getKey(),
            'markable_type' => $markable->getMorphClass(),
            'value' => $value,
        ])->exists();
    }

    public static function toggle(Model $markable, Authenticatable $user, ?string $value = null, array $metadata = [])
    {
        return static::has($markable, $user, $value)
            ? static::remove($markable, $user, $value)
            : static::add($markable, $user, $value, $metadata);
    }
}
