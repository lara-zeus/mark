@php
    use Filament\Support\Facades\FilamentAsset;use Illuminate\Support\Js;use LaraZeus\Mark\MarkServiceProvider;

    $colors = $getColors();
    $statePath = $getStatePath();
    $icons = $getIcons();
    $selectedIcons = $getSelectedIcons();
    $isSequential = $isSequential();
    $disabled = $isDisabled();
    $classes = __('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : '';
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
            icons: @js(array_keys($icons))
        })"
        class="flex flex-wrap gap-5"
    >
        @foreach($icons as $value => $icon)
            @php
                $onClick = 'state = (state === ' . Js::from($value) . ' ? null : ' . Js::from($value) . ')';
                $show = 'isSelected('. Js::from($value) .')';
                $size = 'xl';
            @endphp
            <div>
                <x-filament::icon-button
                    :size="$size"
                    :x-show="$show"
                    :x-on:click="$onClick"
                    :color="$getColor($value)"
                    :icon="$selectedIcons[$value]"
                    :class="$classes"
                    :disabled="$disabled"
                />
                <x-filament::icon-button
                    :size="$size"
                    :x-show="'!' . $show"
                    :x-on:click="$onClick"
                    :color="$getColor($value)"
                    :icon="$icon"
                    :class="$classes"
                    :disabled="$disabled"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
