<?php

namespace LaraZeus\Mark\Forms\Concerns;

trait HasSelectableIcons
{
    /**
     * @var array<string|int, string>|string
     */
    protected array | string $defaultIconsState;

    /**
     * @var array<string|int, string>|string
     */
    protected array | string $selectedIconsState;

    protected bool $isSequential = false;

    /**
     * @param  array<string|int, string>|string  $default
     * @param  array<string|int, string>|string  $selected
     * @return $this
     */
    public function icons(array | string $default, array | string $selected): static
    {
        if (is_array($default) && count($default) === 1) {
            $default = reset($default);
            $selected = reset($selected);
        }

        $this->defaultIconsState = $default;
        $this->selectedIconsState = $selected;

        return $this;
    }

    /**
     * @return array<string|int|bool, string>
     */
    public function getIcons(): array
    {
        return [$this->defaultIconsState, $this->selectedIconsState];
    }

    public function sequential(bool $isSequential = true): static
    {
        $this->isSequential = $isSequential;

        return $this;
    }

    public function isSequential(): bool
    {
        return $this->isSequential;
    }

    public function isMultiple(): bool
    {
        return is_array($this->defaultIconsState);
    }
}
