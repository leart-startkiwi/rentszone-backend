<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            @if (!empty($icon))
                <i class="{{ $icon }}"></i>
            @endif
            {{ $title ?? apply_filters(BASE_ACTION_FORM_ACTIONS_TITLE, trans('core/base::forms.publish')) }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        <x-core::button
            type="submit"
            name="submit"
            value="save"
            color="primary"
            icon="ti ti-coin"
        >
            {{ $saveTitle ?? __('Request') }}
        </x-core::button>
    </x-core::card.body>
</x-core::card>
