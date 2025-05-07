<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasColors
{
    protected array | Arrayable | Closure | null $colors = null;

    public function colors(array | Arrayable | Closure | null $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function getColor(mixed $value): string | array | null
    {
        return $this->getColors()[$value] ?? null;
    }

    public function getColors(): array
    {
        $colors = $this->evaluate($this->colors);

        if ($colors instanceof Arrayable) {
            $colors = $colors->toArray();
        }

        return $colors ?? [];
    }
}
