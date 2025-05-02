@php
    $statePath = $getStatePath();
    $icons = $getIcons();
    $selectedIcons = $getSelectedIcons();
    $colors = $getColors();
    $isSequential = $isSequential();
    $iconsKeys = array_keys($icons);
    // todo move to the component or on the facade?
    $showSelectedValues = function($key) use ($isSequential, $iconsKeys) {
        if(!$isSequential){
            return [(string)$key];
        }
        $keyIndex = array_search($key, $iconsKeys, true);
        $iconsKeys =  array_filter($iconsKeys, fn($key) => $key >= $keyIndex, ARRAY_FILTER_USE_KEY);

        return array_values(array_map(fn($value) => (string) $value, $iconsKeys));
    };
@endphp
<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
>
    <div
            x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
        }"
            class="flex flex-wrap gap-5"
    >
        @foreach($icons as $key => $icon)
            @php
                $values = json_encode($showSelectedValues($key), JSON_THROW_ON_ERROR);
            @endphp
            <div>
                <input
                    name="{{ $statePath }}"
                    type="{{ count($icons) > 1 ? 'radio' : 'checkbox' }}"
                    x-model="state"
                    value="{{ $key }}"
                    class="hidden"
                >
                <x-filament::icon-button
                    :color="$getColor($key)"
                    :icon="$selectedIcons[$key]"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                    x-on:click="state = state === '{{ $key }}' ? null : '{{ $key }}'"
                    x-show="{{ $values }}.includes(state)"
                    size="xl"
                />
                <x-filament::icon-button
                    :color="$getColor($key)"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                    :icon="$icon"
                    x-on:click="state = state === '{{ $key }}' ? null : '{{ $key }}'"
                    x-show="!{{ $values }}.includes(state)"
                    size="xl"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
