@php
    $name = $getName();
    $colors = $getColors();
    $direction = __('filament-panels::layout.direction');
    $state = $getState();
    $icons = $getIcons();
    $isSequential = $isSequential();
    $isMultiple = $isMultiple();

    if ($isMultiple){
        $state = strval($getState());
    }

    $inputName = $name.'-'.$recordKey;
    $inputAttributes= [
        'x-model' => 'state',
    ];

    $buttonsAttributes = [
        ':class'=> "{'pointer-events-none opacity-70': isLoading}",
        'x-bind:disabled'=> "isLoading",
        'x-on:click.stop.prevent' => $isMultiple
        ? 'updateState(state === $el.parentElement.previousElementSibling.value ? \'\' : $el.parentElement.previousElementSibling.value)'
        : 'updateState(!state)'
    ];
@endphp
<div
    x-data="{
        error: undefined,

        isLoading: false,

        name: @js($name),

        recordKey: @js($recordKey),

        state: @js($state),

        async updateState(value) {
            this.isLoading = true

            const response = await this.$wire.updateTableColumnState(
                this.name,
                this.recordKey,
                value,
            )

            this.error = response?.error ?? undefined

            if (!this.error) {
                this.state = value
            }

            this.isLoading = false
        }
    }"

    x-init=" () => {
        Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
            succeed(({ snapshot, effect }) => {
                $nextTick(() => {
                    if (component.id !== @js($this->getId())) {
                        return
                    }

                    if (! $refs.newState) {
                        return
                    }

                    @if($isMultiple)
                        let newState = $refs.newState.value.replaceAll('\\'+String.fromCharCode(34), String.fromCharCode(34))
                    @else
                        let newState = $refs.newState.checked
                    @endif

                    if (state === newState) {
                        return
                    }

                    state = newState
                })
            })
        })
    }"
    x-tooltip="
        error === undefined
            ? false
            : {
                content: error,
                theme: $store.theme,
            }
    "
>
    @if($isMultiple)
        <input
            id="{{ $inputName . '-newState' }}"
            type="hidden"
            value="{{ str($state)->replace('"', '\\"') }}"
            x-ref="newState"
        />
    @else
        <input
            id="{{ $inputName . '-newState' }}"
            type="checkbox"
            class="hidden"
            x-ref="newState"
            @checked($state)
        />
    @endif

    <x-zeus-mark::mark
            :class="$canWrap() ? 'flex-wrap' : ''"
            :name="$inputName"
            :icons="$icons"
            :direction="$direction"
            :colors="$colors ?: 'primary'"
            :sequential="$isSequential"
            :input-attributes="$inputAttributes"
            :default-button-attributes="$buttonsAttributes"
            :selected-button-attributes="$buttonsAttributes"
    />
</div>
