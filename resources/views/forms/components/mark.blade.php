@php
    $statePath = $getStatePath();
    $id = $getId();
    $defaultState = $getDefaultState();
    $colors = $getColors();
    $icons = $getIcons();
    $isMultiple = $isMultiple();
    $isSequential = $isSequential();
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
        :class="$canWrap() ? 'flex-wrap' : ''"
        :name="$id"
        :icons="$icons"
        :direction="__('filament-panels::layout.direction')"
        :colors="$colors ?: 'primary'"
        :sequential="$isSequential"
        :selected-value="$defaultState"
        :input-attributes="['x-model' => 'state']"
        :default-button-attributes="$buttonsAttributes"
        :selected-button-attributes="$buttonsAttributes"
    />
</x-dynamic-component>



