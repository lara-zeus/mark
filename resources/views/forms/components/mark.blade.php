<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $statePath = $getStatePath();
        $icons = $getIcons();
        $selectedIcons = $getSelectedIcons();
        $isSequential = $isSequential();
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
    <div x-data="{
        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
    }" class="flex flex-wrap gap-5">
        @foreach($icons as $key => $icon)
            @php
                $values = json_encode($showSelectedValues($key));
            @endphp
            <div>
                <input type="{{ count($icons) > 1 ? 'radio' : 'checkbox' }}" name="{{ $statePath }}" x-model="state"
                       :value="'{{ $key }}'" class="hidden">
                <x-filament::icon-button
                    x-on:click="state = state === '{{ $key }}' ? null : '{{ $key }}'"
                    x-show="{{ $values }}.includes(state)"
                    icon="{{ $selectedIcons[$key] }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
                <x-filament::icon-button
                    x-on:click="state = state === '{{ $key }}' ? null : '{{ $key }}'"
                    x-show="!{{ $values }}.includes(state)"
                    icon="{{ $icon }}"
                    size="xl"
                    :class="__('filament-panels::layout.direction') === 'rtl' ? '-scale-x-100' : ''"
                />
            </div>
        @endforeach
    </div>
</x-dynamic-component>
