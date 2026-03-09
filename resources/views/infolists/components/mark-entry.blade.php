@php
    use Filament\Infolists\View\Components\IconEntryComponent\IconComponent;use Filament\Support\Enums\IconSize;use Filament\Support\Facades\FilamentColor;$colors = $getColors();
    $state = $getState();
    $icons = $getIcons();
    $isBoolean = $getBoolean();
    $selectedIcons = $getSelectedIcons();
    $isSequential = $isSequential();
    $classes = __('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : '';

    if ($isSequential){
        $stateIndex = array_search($state, array_keys($icons));
    }
@endphp
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag()->class('fi-in-icon flex flex-wrap gap-5') }}>
        @foreach($icons as $value => $icon)
            @php
                $size = IconSize::ExtraLarge;
                $icon = ($isBoolean ? (bool) $value : $value) === $state ? $selectedIcons[$value] : $icon;
                if ($isSequential && $loop->index <= $stateIndex){
                    $icon = $selectedIcons[$value];
                }
            @endphp
                <x-filament::icon
                    :size="$size"
                    :icon="$icon"
                    :class="implode(' ', FilamentColor::getComponentClasses(IconComponent::class, $getColor($value)))"
                />
        @endforeach
    </div>
</x-dynamic-component>
