<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ trans('plugins/car-rentals::booking-reports.booking_summary') }}
                </h2>
                <div class="text-muted mt-1">
                    {{ trans('plugins/car-rentals::booking-reports.date_range_format_value', [
                        'from' => BaseHelper::formatDate($startDate),
                        'to' => BaseHelper::formatDate($endDate),
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                            <x-core::icon name="ti ti-currency-dollar" />
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            {{ format_price(Arr::get($revenue, 'revenue')) }}
                        </div>
                        <div class="text-muted">
                            {{ trans('plugins/car-rentals::booking-reports.total_revenue') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-green text-white avatar">
                            <x-core::icon name="ti ti-calendar-event" />
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            {{ $bookingsCount }}
                        </div>
                        <div class="text-muted">
                            {{ trans('plugins/car-rentals::booking-reports.bookings') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-azure text-white avatar">
                            <x-core::icon name="ti ti-users" />
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            {{ $completedBookings }}
                        </div>
                        <div class="text-muted">
                            {{ trans('plugins/car-rentals::booking-reports.completed') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-yellow text-white avatar">
                            <x-core::icon name="ti ti-clock" />
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">
                            {{ $pendingBookings }}
                        </div>
                        <div class="text-muted">
                            {{ trans('plugins/car-rentals::booking-reports.pending') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>