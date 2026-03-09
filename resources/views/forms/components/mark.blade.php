@php
    use \Illuminate\Support\Js;

    $colors = $getColors();
    $statePath = $getStatePath();
    $icons = $getIcons();
    $isBoolean = $getBoolean();
    $selectedIcons = $getSelectedIcons();
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
        @foreach($icons as $value => $icon)
            @php
                $value = $isBoolean ? (bool) $value : (string) $value;
            @endphp
            <div>
                <x-filament::icon-button
                    :color="$getColor($value)"
                    x-on:click="state = (state === {{ Js::encode($value) }} ? null : {{ Js::encode($value) }})"
                    x-show="state === {{ Js::encode($value) }}"
                    icon="{{ $selectedIcons[$value] }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
                <x-filament::icon-button
                    :color="$getColor($value)"
                    x-on:click="state = (state === {{ Js::encode($value) }} ? null : {{ Js::encode($value) }})"
                    x-show="state !== {{ Js::encode($value) }}"
                    icon="{{ $icon }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
