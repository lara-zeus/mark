@php
    $statePath = $getStatePath();
    $id = $getId();
    $defaultState = $getDefaultState();
    $colors = $getColors();
    [$defaultIconsState, $selectedIconsState] = $getIcons();
    $isSequential = $isSequential();
    $isMultiple = is_array($defaultIconsState) && count($defaultIconsState) > 1;
    $buttonsAttributes = [
        'x-on:click' => $isMultiple
        ? 'state = state === $el.parentElement.previousElementSibling.value ? null : $el.parentElement.previousElementSibling.value'
        : 'state = !state'
    ];
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-zeus-mark::mark
        :x-data="'{
            state: $wire.'.$applyStateBindingModifiers('$entangle(\''.$statePath.'\')').'
        }'"
        :name="$id"
        :default-icons="$defaultIconsState"
        :selected-icons="$selectedIconsState"
        :direction="__('filament-panels::layout.direction')"
        :colors="$colors"
        :sequential="$isSequential"
        :selected-value="$defaultState"
        :input-attributes="['x-model' => 'state']"
        :default-button-attributes="$buttonsAttributes"
        :selected-button-attributes="$buttonsAttributes"
    />
</x-dynamic-component>



