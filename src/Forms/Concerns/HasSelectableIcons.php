<?php

namespace LaraZeus\Mark\Forms\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasSelectableIcons
{
    /**
     * @var array<string|int, string>|Arrayable<string|int, string>|string|Closure
     */
    protected array | string | Arrayable | Closure $defaultIconsState;

    /**
     * @var array<string|int, string>|Arrayable<string|int, string>|string|Closure
     */
    protected array | string | Arrayable | Closure $selectedIconsState;

    protected bool $isSequential = false;

    /**
     * @param  array<string|int, string>|Arrayable<string|int, string>|string|Closure  $default
     * @param  array<string|int, string>|Arrayable<string|int, string>|string|Closure  $selected
     * @return $this
     */
    public function icons(array | string | Arrayable | Closure $default, array | string | Arrayable | Closure $selected): static
    {
        $this->defaultIconsState = $default;
        $this->selectedIconsState = $selected;

        return $this;
    }

    /**
     * @return array<string|int|bool, string>
     */
    public function getIcons(): array
    {
        return [
            $this->getIconsState($this->defaultIconsState),
            $this->getIconsState($this->selectedIconsState),
        ];
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
        $icons = $this->getIconsState($this->defaultIconsState);

        return is_array($icons) && count($icons) > 1;
    }

    /**
     * @param  array<string|int, string>|Arrayable<string|int, string>|string|Closure  $iconState
     * @return array<string|int, string>|string
     */
    protected function getIconsState(array | Arrayable | Closure | string $iconState): array | string
    {
        $state = $this->evaluate($iconState);

        if ($state instanceof Arrayable) {
            $state = $state->toArray();
        }

        if (is_array($state) && count($state) === 1) {
            $state = reset($state);
        }

        return $state;
    }

    public function like(): static
    {
        return $this
            ->icons(
                [
                    'heroicon-o-hand-thumb-down',
                    'heroicon-o-hand-thumb-up',
                ],
                [
                    'heroicon-s-hand-thumb-down',
                    'heroicon-s-hand-thumb-up',
                ]
            );
    }

    public function bookmark(): static
    {
        return $this->icons('heroicon-o-bookmark', 'heroicon-s-bookmark');
    }

    public function rating(): static
    {
        return $this
            ->icons(
                array_fill(1, 5, 'heroicon-o-star'),
                array_fill(1, 5, 'heroicon-s-star')
            )
            ->sequential();
    }
}
