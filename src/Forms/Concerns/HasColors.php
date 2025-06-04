<?php

namespace LaraZeus\Mark\Forms\Concerns;

trait HasColors
{
    /**
     * @var array<string|int, string>|string|null
     */
    protected array | string | null $colors = null;

    /**
     * @param  array<string|int, string>|string|null  $colors
     */
    public function colors(array | string | null $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return array<string|int, string>
     */
    public function getColors(): array
    {
        return $this->colors ?? [];
    }
}
