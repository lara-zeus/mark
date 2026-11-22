@php
    $statePath = $getStatePath();
    $icons = $getIcons();
    $selectedIcons = $getSelectedIcons();
    $isSequential = $isSequential();
    $isDisabled = $isDisabled();
    $keys = array_keys($icons);
    $showSelectedValues = function($key) use ($isSequential, $keys) {
        if(!$isSequential){
            return [(string)$key];
        }
        $keyIndex = array_search($key, $keys);
        $keys =  array_filter($keys, fn($key) => $key >= $keyIndex, ARRAY_FILTER_USE_KEY);

        return array_values(array_map(fn($value) => (string) $value, $keys));
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
        class="flex flex-wrap gap-1 sm:gap-2"
    >
        @foreach($icons as $key => $icon)
            @php
                $values = json_encode($showSelectedValues($key), JSON_THROW_ON_ERROR);
            @endphp
            <div>
                <input
                    type="{{ count($icons) > 1 ? 'radio' : 'checkbox' }}"
                    name="{{ $statePath }}"
                    x-model="state"
                    :value="'{{ $key }}'"
                    @disabled($isDisabled)
                    class="hidden"
                >
                <x-filament::icon-button
                    :color="$getColor($key) ?? 'primary'"
                    :disabled="$isDisabled"
                    @if (! $isDisabled)
                        x-on:click="state = String(state) === '{{ $key }}' ? null : '{{ $key }}'"
                    @endif
                    x-show="state !== null && state !== undefined && {{ $values }}.includes(String(state))"
                    icon="{{ $selectedIcons[$key] }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
                <x-filament::icon-button
                    :color="$getColor($key) ?? 'primary'"
                    :disabled="$isDisabled"
                    @if (! $isDisabled)
                        x-on:click="state = String(state) === '{{ $key }}' ? null : '{{ $key }}'"
                    @endif
                    x-show="state === null || state === undefined || !{{ $values }}.includes(String(state))"
                    icon="{{ $icon }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
