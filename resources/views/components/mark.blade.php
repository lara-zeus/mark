@php
    use Illuminate\View\ComponentAttributeBag;use LaraZeus\Mark\NotPassed;
@endphp

@props([
    'name',
    'defaultIcons',
    'selectedIcons',
    'colors' => 'primary',
    'direction' => 'ltr',
    'disabled' => false,
    'readOnly' => false,
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

    $commonAttributesFn = fn (ComponentAttributeBag $attributes) => $attributes
            ->class([
                '-scale-x-100' => $direction === 'rtl',
                'pointer-events-none' => $readOnly,
            ])
            ->merge([
                'size' => 'xl',
                'disabled' => $disabled,
                'wire:loading.attr' => false,
            ])
            ->when($attributes->whereStartsWith('x-on:click')->isEmpty(), fn($attrs) => $attrs->merge([
                'x-on:click' => '$el.parentElement.firstElementChild.checked = !$el.parentElement.firstElementChild.checked'
            ]));

    $defaultButtonAttributes = $commonAttributesFn($defaultButtonAttributes);

    $selectedButtonAttributes = $commonAttributesFn($selectedButtonAttributes);
@endphp

<div
    {{ $attributes
        ->merge(['x-data' => ''])
        ->class("zeus-mark")
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
