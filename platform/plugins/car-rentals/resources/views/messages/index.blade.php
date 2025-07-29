@if ($message)
    <x-core::datagrid>
        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/car-rentals::message.time') }}
            </x-slot:title>

            {{ $message->created_at }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/car-rentals::message.message_id') }}
            </x-slot:title>

            AB00000{{ $message->id }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/car-rentals::message.form_name') }}
            </x-slot:title>

            {{ $message->name }}
        </x-core::datagrid.item>

        @if ($message->ip_address && auth()->check())
            <x-core::datagrid.item>
                <x-slot:title>
                    {{ trans('plugins/car-rentals::message.ip_address') }}
                </x-slot:title>

                <a href="https://ipinfo.io/{{ $message->ip_address }}" target="_blank">
                    {{ $message->ip_address }}
                </a>
            </x-core::datagrid.item>
        @endif

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/car-rentals::message.email.header') }}
            </x-slot:title>

            <a href="mailto:{{ $message->email }}">
                {{ $message->email }}
            </a>
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/car-rentals::message.phone') }}
            </x-slot:title>

            @if ($message->phone)
                <a href="tel:{{ $message->phone }}">
                    {{ $message->phone }}
                </a>
            @else
                N/A
            @endif
        </x-core::datagrid.item>

        @if ($message->car_id && $message->car)
            <x-core::datagrid.item>
                <x-slot:title>
                    {{ trans('plugins/car-rentals::message.car') }}
                </x-slot:title>

                <a href="{{ $message->car->url }}" target="_blank">
                    {{ $message->car->name }}
                </a>
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>

    <x-core::datagrid.item class="mt-4">
        <x-slot:title>
            {{ trans('plugins/car-rentals::message.content') }}
        </x-slot:title>

        {{ $message->content ?: 'N/A' }}
    </x-core::datagrid.item>
@endif
