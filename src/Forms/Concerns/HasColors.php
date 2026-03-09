<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

trait HasColors
{
    /**
     * @var array<string>|Arrayable<array-key, string>|Closure|null
     */
    protected array | string | Arrayable | Closure | null $colors = null;

    /**
     * @param  array<string>|Arrayable<array-key, string>|Closure|null  $colors
     * @return $this
     */
    public function colors(array | Arrayable | Closure | null $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return string|array<string>
     */
    public function getColor(mixed $value): string | array
    {
        return $this->getColors()[$value] ?? 'primary';
    }

    /**
     * @return array<string>
     */
    public function getColors(): array
    {
        $colors = $this->evaluate($this->colors);

        if ($colors instanceof Arrayable) {
            $colors = $colors->toArray();
        }

        return Arr::wrap($colors ?? []);
    }
}
