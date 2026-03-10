@php
    use Filament\Support\Facades\FilamentAsset;use Illuminate\Support\Js;use LaraZeus\Mark\MarkServiceProvider;

    $colors = $getColors();
    $state = $getState();
    $name = $getName();
    $icons = $getIcons();
    $selectedIcons = $getSelectedIcons();
    $isSequential = $isSequential();
    $disabled = $isDisabled();
    $classes = __('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : '';
@endphp

<div {{ $getExtraAttributeBag()->class('px-3 py-4') }}>
    <div
        x-load
        x-load-src="{{ FilamentAsset::getAlpineComponentSrc('mark', MarkServiceProvider::$name) }}"
        x-data="zeusMark({
            state: @js($state),
            isSequential: @js($isSequential),
            icons: @js(array_keys($icons))
        })"
        x-init="
            $watch('state', () => {
                $wire.updateTableColumnState(
                    @js($name),
                    @js($recordKey),
                    state
                )
            })
        "
        class="flex gap-5"
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
</div>
