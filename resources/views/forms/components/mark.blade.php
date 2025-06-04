@php
    $statePath = $getStatePath();
    $colors = $getColors();
    [$defaultIconsState, $selectedIconsState] = $getIcons();
    $isMultiple = $isMultiple();
    $isSequential = $isSequential();
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
        @if($isMultiple)
            @foreach($defaultIconsState as $value => $state)
                @php
                    $selectedState = $selectedIconsState[$value];
                    if($isSequential) {
                        $keys = array_reverse(array_keys($defaultIconsState));
                        $index = array_search($value, $keys, true);
                        $values = array_slice($keys, 0, $index + 1);
                        $values = json_encode($values, JSON_THROW_ON_ERROR);
                    } else {
                        $values = json_encode([$value], JSON_THROW_ON_ERROR);
                    }
                    $color = $colors[$value] ?? 'primary';
                    $value = json_encode($value, JSON_THROW_ON_ERROR);
                @endphp
                <div>
                    <input
                        :value="{{ $value }}"
                        x-model="state"
                        name="{{ $statePath }}"
                        type="radio"
                        class="pointer-events-none absolute opacity-0"
                    >
                    <x-filament::icon-button
                        :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                        :color="$color"
                        :icon="$state"
                        x-on:click="state = state === {{ $value }} ? null : {{ $value }}"
                        x-show="!{{ $values }}.includes(state)"
                        size="xl"
                    />
                    <x-filament::icon-button
                        :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                        :color="$color"
                        :icon="$selectedState"
                        x-on:click="state = state === {{ $value }} ? null : {{ $value }}"
                        x-show="{{ $values }}.includes(state)"
                        size="xl"
                    />
                </div>
            @endforeach
        @else
            @php
                $color = $colors[$defaultIconsState] ?? 'primary';
            @endphp
            <div>
                <input
                    x-model="state"
                    type="checkbox"
                    class="pointer-events-none absolute opacity-0"
                >
                <x-filament::icon-button
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                    :color="$color"
                    :icon="$defaultIconsState"
                    x-show="!state"
                    x-on:click="state = !state"
                    size="xl"
                />
                <x-filament::icon-button
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                    :color="$color"
                    :icon="$selectedIconsState"
                    x-show="state"
                    x-on:click="state = !state"
                    size="xl"
                />
            </div>
        @endif
    </div>
</x-dynamic-component>
