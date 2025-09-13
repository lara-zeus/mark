@php
    $state = $getState();
    $name = $getName();
    $icons = $getIcons();
    $colors = $getColors();
    $isSequential = $isSequential();
    $direction = __('filament-panels::layout.direction');
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <x-zeus-mark::mark
        :class="$canWrap() ? 'flex-wrap' : ''"
        :name="$name"
        :icons="$icons"
        :direction="$direction"
        :colors="$colors ?: 'primary'"
        :sequential="$isSequential"
        :selected-value="$state"
        read-only
    />
</x-dynamic-component>
