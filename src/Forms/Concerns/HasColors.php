<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasColors
{
    /**
     * @var array<string|int, string>|Arrayable<string|int, string>|string|null
     */
    protected array|string|Arrayable|Closure|null $colors = null;

    /**
     * @param  array<string|int, string>|Arrayable<string|int, string>|string  $colors
     */
    public function colors(array|string|Arrayable|Closure $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return array<string|int, string>
     */
    public function getColors(): array
    {
        $colors = $this->evaluate($this->colors);

        if ($colors instanceof Arrayable) {
            $colors = $colors->toArray();
        }

        return $colors ?? [];
    }
}
