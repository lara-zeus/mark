<?php

namespace Larazeus\Mark\Forms\Concerns;

use Closure;

trait HasSelectableIcons
{
    protected array | Closure | null $icons = null;

    protected array | Closure | null $selectedIcons = null;

    protected bool | Closure | null $isSequenceSelection = false;

    public function icons(array $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    public function getIcons(): array
    {
        return $this->evaluate($this->icons);
    }

    public function selectedIcons(array $selectedIcons): static
    {
        $this->selectedIcons = $selectedIcons;

        return $this;
    }

    public function getSelectedIcons(): array
    {
        return $this->evaluate($this->selectedIcons);
    }

    public function isSequenceSelection(bool $isSequenceSelection = true): static
    {
        $this->isSequenceSelection = $isSequenceSelection;

        return $this;
    }

    public function getIsSequenceSelection(): bool
    {
        return $this->evaluate($this->isSequenceSelection);
    }
}
