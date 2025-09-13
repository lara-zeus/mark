@php
    use Illuminate\Support\Arr;
    use Illuminate\View\ComponentAttributeBag;
    use LaraZeus\Mark\NotPassed;
@endphp

@props([
    'name',
    'icons',
    'colors' => 'primary',
    'direction' => 'ltr',
    'disabled' => false,
    'readOnly' => false,
    'selectedValue' => new NotPassed,
    'inputAttributes' => [],
    'defaultButtonAttributes' => [],
    'selectedButtonAttributes' => [],
])

@php
    [$defaultIcons, $selectedIcons] = $icons;

    $defaultIcons = Arr::wrap($defaultIcons);
    $selectedIcons = Arr::wrap($selectedIcons);

    $isMultiple =  count($defaultIcons) > 1;

    if ($selectedValue instanceof NotPassed){
        $selectedValue = $isMultiple ? null : false;
    }

    $initAttributesBag = function( &$attrs ) {
        $attrs = $attrs instanceof ComponentAttributeBag ? $attrs : new ComponentAttributeBag($attrs);
    };

    $initAttributesBag($inputAttributes);
    $initAttributesBag($defaultButtonAttributes);
    $initAttributesBag($selectedButtonAttributes);

    $commonAttributesFn = fn (ComponentAttributeBag &$attributes) => $attributes = $attributes
            ->class([
                '-scale-x-100' => $direction === 'rtl',
                'pointer-events-none opacity-70' => $disabled,
                'pointer-events-none' => $readOnly,
            ])
            ->merge([
                'size' => 'xl',
                'disabled' => $disabled || $readOnly,
                'wire:loading.attr' => false,
            ])
            ->when($attributes->whereStartsWith('x-on:click')->isEmpty(), fn($attrs) => $attrs->merge([
                'x-on:click' => '$el.parentElement.firstElementChild.checked = !$el.parentElement.firstElementChild.checked'
            ]));

    $commonAttributesFn($defaultButtonAttributes);
    $commonAttributesFn($selectedButtonAttributes);

    $idFn = fn($name, $value) => $isMultiple ? "$name-$value" : $name;
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
                    'type' => $isMultiple ? 'radio' : 'checkbox',
                    'disabled' => $disabled
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
