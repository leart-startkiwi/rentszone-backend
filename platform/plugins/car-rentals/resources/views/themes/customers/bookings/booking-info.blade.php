@php
    $route ??= 'invoices.generate';
    $buttonClass ??= 'btn-primary';
@endphp

@if ($booking)
    <div class="row">
        <div class="col-lg-4">
            <strong>{{ __('Booking Information') }}:</strong> {{ $booking->booking_number }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Time') }}:</strong> {{ $booking->created_at }}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <strong>{{ __('Full Name') }}:</strong> {{ $booking->customer_name }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Email') }}:</strong> <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
        </div>

        @if($customerPhone = $booking->customer_phone)
            <div class="col-lg-4">
                <strong>{{ __('Phone') }}:</strong> <a href="mailto:{{ $customerPhone }}">{{ $customerPhone }}</a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-4">
            <strong>{{ __('Car') }}:</strong>
            @if ($booking->car->car->exists && ($car = $booking->car->car))
                <a href="{{ $car->url }}" target="_blank">{{ $car->name }}</a>
            @else
                {{ $booking->car->car_name }}
            @endif
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Rental Start Date') }}:</strong>
            {{ $booking->car->rental_start_date->toDateString() }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Rental End Date') }}:</strong>
            {{ $booking->car->rental_end_date->toDateString() }}
        </div>
    </div>

    <div class="mb-4 mt-4">
        <h6>{{ __('Car') }}</h6>
        <x-core::table>
            <x-core::table.header>
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
                    <x-core::table.body.cell
                        class="text-start"
                    >{{ $booking->car->car_name }}</x-core::table.body.cell>
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

    @if ($booking->services->isNotEmpty())
        <h6>{{ __('Services') }}</h6>
        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell>
                    {{ __('Name') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Price') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-center">
                    {{ __('Total') }}
                </x-core::table.header.cell>
            </x-core::table.header>
            <x-core::table.body>
                @foreach ($booking->services->unique() as $service)
                    <x-core::table.body.row>
                        <x-core::table.body.cell style="vertical-align: middle !important;">
                            {{ $service->name }}
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            {{ format_price($service->price) }}
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            {{ format_price($service->price) }}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                @endforeach
            </x-core::table.body>
        </x-core::table>
        <br>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <strong>{{ __('Sub Total') }}:</strong>
            {{ format_price($booking->sub_total) }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Discount Amount') }}:</strong>
            {{ format_price($booking->coupon_amount) }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Tax Amount') }}:</strong>
            {{ format_price($booking->tax_amount) }}
        </div>

        <div class="col-lg-4">
            <strong>{{ __('Total') }}:</strong>
            {{ format_price($booking->amount) }}
        </div>

        @if (is_plugin_active('payment') && $booking->payment->id)
            @auth
                <div class="col-lg-4">
                    <strong>{{ __('Payment ID') }}:</strong>
                    <a href="{{ route('payment.show', $booking->payment->id) }}" target="_blank">
                        {{ $booking->payment->charge_id }}
                        <x-core::icon name="ti ti-external-link" />
                    </a>
                </div>
            @endauth

            <div class="col-lg-4">
                <strong>{{ __('Payment method') }}:</strong>  {{ $booking->payment->payment_channel->label() }}
            </div>

            <div class="col-lg-4">
                <strong>{{ __('Payment status') }}:</strong>  {!! $booking->payment->status->toHtml() !!}
            </div>

            @if ($booking->payment->payment_channel == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER
                && $booking->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING
            )
                <div class="col-lg-4">
                    <strong>{{ __('Payment info') }}:</strong>
                    {!! BaseHelper::clean(get_payment_setting('description', $booking->payment->payment_channel)) !!}
                </div>
            @endif

            @if ($displayBookingStatus ?? false)
                <div class="col-lg-4">
                    <strong>{{ __('Booking status') }}:</strong>
                    {!! BaseHelper::clean($booking->status->toHtml()) !!}
                </div>
            @endif
        @endif
    </div>

    @if ((auth()->check() || $booking->customer_id) && ($invoiceId = $booking->invoice->id) && $route)
        <div class="btn-list d-flex gap-2 mt-4">
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
