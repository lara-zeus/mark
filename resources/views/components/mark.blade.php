@php
    use Illuminate\View\ComponentAttributeBag;
    use LaraZeus\Mark\NotPassed;
@endphp

@props([
    'name',
    'defaultIcons',
    'selectedIcons',
    'colors' => 'primary',
    'direction' => 'ltr',
    'inputAttributes' => [],
    'defaultButtonAttributes' => [],
    'selectedButtonAttributes' => [],
    'selectedValue' => new NotPassed,
])

@php
    $isMultiple = is_array($defaultIcons) && count($defaultIcons) > 1;
    $idFn = fn($name, $value) => $isMultiple ? "$name-$value" : $name;

    if(!$isMultiple && !is_array($defaultIcons)){
        $defaultIcons = [$defaultIcons];
        $selectedIcons = [$selectedIcons];
    }

    if ($selectedValue instanceof NotPassed){
        $selectedValue = $isMultiple ? null : false;
    }

    if (empty($colors)){
        $colors = 'primary';
    }

    if (is_array($inputAttributes)){
        $inputAttributes = new ComponentAttributeBag($inputAttributes);
    }
    if (is_array($defaultButtonAttributes)){
        $defaultButtonAttributes = new ComponentAttributeBag($defaultButtonAttributes);
    }
    if (is_array($selectedButtonAttributes)){
        $selectedButtonAttributes = new ComponentAttributeBag($selectedButtonAttributes);
    }

    $defaultButtonAttributes = $defaultButtonAttributes
        ->class([
            '-scale-x-100' => $direction === 'rtl',
        ])
        ->merge([
            'size' => 'xl',
            'x-on:click' => '$el.parentElement.firstElementChild.checked = !$el.parentElement.firstElementChild.checked'
        ], false);

    $selectedButtonAttributes = $selectedButtonAttributes
        ->class([
            '-scale-x-100' => $direction === 'rtl',
        ])
        ->merge([
            'size' => 'xl',
            'x-on:click' => '$el.parentElement.firstElementChild.checked = !$el.parentElement.firstElementChild.checked'
        ], false);

@endphp

<div
    {{ $attributes
        ->merge(['x-data' => ''])
        ->class("zeus-mark flex flex-wrap gap-5")
    }}
>
    @foreach($defaultIcons as $value => $defaultIcon)
        @php
            $iconColor = is_array($colors) ? $colors[$value] : $colors;
        @endphp
        <input
            {{ $inputAttributes
                ->class('sr-only')
                ->merge([
                    'id' => $idFn($name, $value),
                    'name' => $name,
                    'type' => $isMultiple ? 'radio' : 'checkbox'
                ], false)
                ->when($isMultiple, fn($attrs) => $attrs->merge([
                    'value' => $value
                ], false))
                ->when(($isMultiple ? $selectedValue === $value : $selectedValue), fn($attrs) => $attrs->merge([
                    'checked' => ''
                ]))
            }}
        >
        <div>
            <x-filament::icon-button
                :attributes="
                    $defaultButtonAttributes
                    ->merge([
                     'color' => $iconColor,
                     'icon' => $defaultIcon,
                    ], false)
                "
            />
            <x-filament::icon-button
                :attributes="
                    $selectedButtonAttributes
                    ->merge([
                     'color' => $iconColor,
                     'icon' => $selectedIcons[$value],
                    ], false)
                "
            />
        </div>
    @endforeach
</div>
