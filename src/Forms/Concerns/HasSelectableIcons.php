<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;

trait HasSelectableIcons
{
    /**
     * @var array<string|int|bool, string>|Closure|null
     */
    protected array | Closure | null $icons = null;

    /**
     * @var array<string|int|bool, string>|Closure|null
     */
    protected array | Closure | null $selectedIcons = null;

    protected bool | Closure | null $isSequential = false;

    /**
     * @param  array<string|int|bool, string>  $icons
     * @return $this
     */
    public function icons(array $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * @return array<string|int|bool, string>
     */
    public function getIcons(): array
    {
        return $this->evaluate($this->icons);
    }

    /**
     * @param  array<string|int|bool, string>  $selectedIcons
     * @return $this
     */
    public function selectedIcons(array $selectedIcons): static
    {
        $this->selectedIcons = $selectedIcons;

        return $this;
    }

    /**
     * @return array<string|int|bool, string>
     */
    public function getSelectedIcons(): array
    {
        return $this->evaluate($this->selectedIcons);
    }

    public function sequential(bool $isSequential = true): static
    {
        $this->isSequential = $isSequential;

        return $this;
    }

    public function isSequential(): bool
    {
        return $this->evaluate($this->isSequential);
    }
}
