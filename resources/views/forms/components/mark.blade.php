@php
    use Filament\Support\Facades\FilamentAsset;use \Illuminate\Support\Js;use LaraZeus\Mark\MarkServiceProvider;

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
        x-load
        x-load-src="{{ FilamentAsset::getAlpineComponentSrc('mark', MarkServiceProvider::$name) }}"
        x-data="zeusMark({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            isSequential: @js($isSequential),
            icons: @js(array_map(fn($value) => $isBoolean ? (bool) $value : (string) $value, array_keys($icons)))
        })"
        class="flex flex-wrap gap-5"
    >
        @foreach($icons as $value => $icon)
            @php
                $value = $isBoolean ? (bool) $value : (string) $value;
            @endphp
            <div>
                <x-filament::icon-button
                    color="{{ $getColor($value) }}"
                    x-on:click="state = (state === {{ Js::from($value) }} ? null : {{ Js::from($value) }})"
                    x-show="isSelected({{ Js::from($value) }})"
                    icon="{{ $selectedIcons[$value] }}"
                    size="xl"
                    class="{{ __('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : '' }}"
                />
                <x-filament::icon-button
                    color="{{ $getColor($value) }}"
                    x-on:click="state = (state === {{ Js::from($value) }} ? null : {{ Js::from($value) }})"
                    x-show="! isSelected({{ Js::from($value) }})"
                    icon="{{ $icon }}"
                    size="xl"
                    class="{{ __('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : '' }}"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
