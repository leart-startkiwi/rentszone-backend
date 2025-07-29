@php
    $route ??= 'invoices.generate';
@endphp

@if ($booking)
    <x-core::datagrid class="mb-4">
        <x-core::datagrid.item :title="__('Booking Number')">
            {{ $booking->booking_number }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Time')">
            {{ $booking->created_at }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Full Name')">
            {{ $booking->customer_name }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Email')">
            <a href="mailto:{{ $booking->customer->email }}">{{ $booking->customer->email }}</a>
        </x-core::datagrid.item>

        @if ($booking->customer->phone)
            <x-core::datagrid.item :title="__('Phone')">
                <a href="tel:{{ $booking->customer->phone }}">{{ $booking->customer->phone }}</a>
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>

    <x-core::datagrid class="mb-4">
        <x-core::datagrid.item :title="__('Car')">
            {{ $booking->car->car_name }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Rental Start Date')">
            {{ $booking->car->rental_start_date }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Rental End Date')">
            {{ $booking->car->rental_end_date }}
        </x-core::datagrid.item>

        @if ($booking->note)
            <x-core::datagrid.item :title="__('Note')">
                {{ $booking->note }}
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>

    <div class="mb-4">
        <h4>{{ __('Car') }}</h4>
        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell class="text-center" style="width: 150px;">
                    {{ __('Image') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ __('Name') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Rental Start Date') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Rental End Date') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Price') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Tax') }}
                </x-core::table.header.cell>
            </x-core::table.header>
            <x-core::table.body>
                <x-core::table.body.row>
                    @if ($booking->car->car)
                        <x-core::table.body.cell
                                class="text-center"
                                style="width: 150px; vertical-align: middle !important;"
                        >
                            <a
                                    href="{{ $booking->car->car->url }}"
                                    target="_blank"
                            >
                                <img
                                        src="{{ RvMedia::getImageUrl($booking->car->car->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                        alt="{{ $booking->car->car_name }}"
                                        width="140"
                                >
                            </a>
                        </x-core::table.body.cell>
                        <x-core::table.body.cell style="vertical-align: middle !important;"><a
                                    class="booking-information-link"
                                    href="{{ $booking->car->car->url }}"
                                    target="_blank"
                            >{{ $booking->car->car_name }}</a></x-core::table.body.cell>
                    @else
                        <x-core::table.body.cell>
                            <img
                                src="{{ RvMedia::getImageUrl($booking->car->car_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $booking->car->car_name }}"
                                width="140"
                            >
                        </x-core::table.body.cell>
                        <x-core::table.body.cell style="vertical-align: middle !important;">{{ $booking->car->car_name }}</x-core::table.body.cell>
                    @endif
                    <x-core::table.body.cell
                            class="text-center"
                            style="vertical-align: middle !important;"
                    >{{ $booking->car->rental_start_date }}</x-core::table.body.cell>
                    <x-core::table.body.cell
                            class="text-center"
                            style="vertical-align: middle !important;"
                    >{{ $booking->car->rental_end_date }}</x-core::table.body.cell>
                    <x-core::table.body.cell
                            class="text-center"
                            style="vertical-align: middle !important;"
                    ><strong>{{ format_price($booking->car->price) }}</strong></x-core::table.body.cell>
                    <x-core::table.body.cell
                            class="text-center"
                            style="vertical-align: middle !important;"
                    ><strong>{{ format_price($booking->tax_amount) }}</strong></x-core::table.body.cell>
                </x-core::table.body.row>
            </x-core::table.body>
        </x-core::table>
    </div>

    @if($services = $booking->services)
        <div class="mb-4">
            <h4>{{ __('Services') }}</h4>
            <x-core::table>
                <x-core::table.header>
                    <x-core::table.header.cell class="text-center" style="width: 150px;">
                        {{ __('Image') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ __('Name') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-center">
                        {{ __('Price') }}
                    </x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @foreach($services as $service)
                        <x-core::table.body.row>
                            <x-core::table.body.cell
                                class="text-center"
                                style="width: 150px; vertical-align: middle !important;"
                            >
                                <a
                                    href="{{ $service->url }}"
                                    target="_blank"
                                >
                                    <img
                                        src="{{ RvMedia::getImageUrl($service->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                        alt="{{ $service->name }}"
                                        width="140"
                                    >
                                </a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell style="vertical-align: middle !important;">{{ $service->name }}</x-core::table.body.cell>
                            <x-core::table.body.cell
                                class="text-center"
                                style="vertical-align: middle !important;"
                            ><strong>{{ format_price($service->price) }}</strong></x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforeach
                </x-core::table.body>
            </x-core::table>
        </div>
    @endif


    <x-core::datagrid>
        <x-core::datagrid.item :title="__('Sub Total')">
            {{ format_price($booking->sub_total) }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Discount Amount')">
            {{ format_price($booking->coupon_amount) }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Tax Amount')">
            {{ format_price($booking->tax_amount) }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Total Amount')">
            {{ format_price($booking->amount) }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Status')">
            {!! $booking->status->toHtml() !!}
        </x-core::datagrid.item>

        @if (is_plugin_active('payment') && $booking->payment->id)
            @auth
                <x-core::datagrid.item :title="__('Payment ID')">
                    <a href="{{ route('payment.show', $booking->payment->id) }}" target="_blank">
                        {{ $booking->payment->charge_id }}
                        <x-core::icon name="ti ti-external-link" />
                    </a>
                </x-core::datagrid.item>
            @endauth

            <x-core::datagrid.item :title="__('Payment method')">
                {{ $booking->payment->payment_channel->label() }}
            </x-core::datagrid.item>

            <x-core::datagrid.item :title="__('Payment status')">
                {!! $booking->payment->status->toHtml() !!}
            </x-core::datagrid.item>

            @if ($booking->payment->payment_channel == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER
                && $booking->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING
            )
                <x-core::datagrid.item :title="__('Payment info')">
                    {!! BaseHelper::clean(get_payment_setting('description', $booking->payment->payment_channel)) !!}
                </x-core::datagrid.item>
            @endif

            @if ($displayBookingStatus ?? false)
                <x-core::datagrid.item :title="__('Booking status')">
                    {!! $booking->status->toHtml() !!}
                </x-core::datagrid.item>
            @endif
        @endif
    </x-core::datagrid>

    @if ((auth()->check() || $booking->customer_id) && ($invoiceId = $booking->invoice->id) && $route)
        <div class="btn-list mt-5">
            <x-core::button
                    tag="a"
                    :href="route($route, ['invoice' => $invoiceId, 'type' => 'print'])"
                    target="_blank"
                    icon="ti ti-printer"
                    :class="$buttonClass ?? ''"
            >
                {{ __('View Invoice') }}
            </x-core::button>
            <x-core::button
                    tag="a"
                    :href="route($route, ['invoice' => $invoiceId, 'type' => 'download'])"
                    target="_blank"
                    icon="ti ti-download"
                    :class="$buttonClass ?? ''"
            >
                {{ __('Download Invoice') }}
            </x-core::button>
        </div>
    @endif
@endif
