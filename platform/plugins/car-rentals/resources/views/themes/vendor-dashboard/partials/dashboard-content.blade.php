<div class="row mb-3 mt-5">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="ps-block--stat yellow">
            <div class="ps-block__left"><span><x-core::icon name="ti ti-car" /></span></div>
            <div class="ps-block__content">
                <p>{{ __('Cars') }}</p>
                <h4>{{ $totalCars }}</h4>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="ps-block--stat pink">
            <div class="ps-block__left"><span><x-core::icon name="ti ti-wallet" /></span></div>
            <div class="ps-block__content">
                <p>{{ __('Revenue') }}</p>
                <h4>{{ format_price($data['revenue']['amount']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="ps-block--stat green">
            <div class="ps-block__left"><span><x-core::icon name="ti ti-calendar-event" /></span></div>
            <div class="ps-block__content">
                <p>{{ __('Bookings') }}</p>
                <h4>{{ $totalBookings }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="ps-section__left">
    @if (!$totalCars)
        <div class="card bg-light border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle p-3 me-3 position-relative">
                                <x-core::icon name="ti ti-car" class="text-white" style="width: 2rem; height: 2rem;" />
                                <span class="position-absolute" style="bottom: 5px; right: 5px;">
                                    <x-core::icon name="ti ti-plus" class="text-white bg-success rounded-circle p-1" style="width: 1.2rem; height: 1.2rem;" />
                                </span>
                            </div>
                            <h3 class="mb-0">{{ __('You have no cars yet') }}</h3>
                        </div>
                        <p class="fs-5 mb-3">{{ __('Start your car rental business by adding your first vehicle to your fleet.') }}</p>
                        <a href="{{ route('car-rentals.vendor.cars.create') }}" class="btn btn-primary">
                            <x-core::icon name="ti ti-plus" class="me-1" /> {{ __('Add Your First Car') }}
                        </a>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-center">
                        <x-core::icon name="ti ti-car" class="text-muted" style="width: 8rem; height: 8rem; opacity: 0.2;" />
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <x-core::card class="mb-3">
                    <x-core::card.header>
                        <div>
                            <x-core::card.title>{{ __('Sales Reports') }}</x-core::card.title>
                            <x-core::card.subtitle>
                                <a href="{{ route('car-rentals.vendor.revenues.index') }}">
                                    {{ __('Revenues in :label', ['label' => $data['predefinedRange']]) }}
                                    <x-core::icon name="ti ti-arrow-right" />
                                </a>
                            </x-core::card.subtitle>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-secondary dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{ $data['predefinedRange'] }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.today')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.today') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.yesterday')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.yesterday') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.this_week')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.this_week') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.last_7_days')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.last_7_days') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.last_30_days')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.last_30_days') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.this_month')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.this_month') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('car-rentals.vendor.dashboard', ['date_range' => trans('plugins/car-rentals::reports.ranges.last_month')]) }}"
                                        >
                                            {{ trans('plugins/car-rentals::reports.ranges.last_month') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </x-core::card.header>
                    <x-core::card.body>
                        <div id="revenue-chart">
                            <revenue-chart-component
                                url="{{ route('car-rentals.vendor.chart.month') }}?date_from={{ $data['startDate'] }}&date_to={{ $data['endDate'] }}"
                            ></revenue-chart-component>
                        </div>
                    </x-core::card.body>
                </x-core::card>

                <x-core::card>
                    <x-core::card.header>
                        <div>
                            <x-core::card.title>{{ __('Recent Bookings') }}</x-core::card.title>
                            <x-core::card.subtitle>
                                <a href="{{ route('car-rentals.vendor.bookings.index') }}">
                                    {{ __('View Full Bookings List') }}
                                    <x-core::icon name="ti ti-arrow-right" />
                                </a>
                            </x-core::card.subtitle>
                        </div>
                    </x-core::card.header>
                    <x-core::card.body>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Car') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['bookings'] as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>
                                                @if ($booking->car && $booking->car->car)
                                                    <a href="{{ route('car-rentals.vendor.cars.edit', $booking->car->car->id) }}">
                                                        {{ $booking->car->car->name }}
                                                    </a>
                                                @else
                                                    &mdash;
                                                @endif
                                            </td>
                                            <td>{{ format_price($booking->amount) }}</td>
                                            <td>{!! $booking->status->toHtml() !!}</td>
                                            <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">{{ __('No bookings found!') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-core::card.body>
                </x-core::card>
            </div>

            <div class="col-md-4">
                <x-core::card>
                    <x-core::card.header>
                        <div>
                            <x-core::card.title>{{ __('Earnings') }}</x-core::card.title>
                            <x-core::card.subtitle>{{ __('Earnings in :label', ['label' => $data['predefinedRange']]) }}</x-core::card.subtitle>
                        </div>
                    </x-core::card.header>
                    <x-core::card.body>
                        <div id="revenue-chart">
                            <revenue-chart
                                :data="{{ json_encode([
                                        ['label' => __('Revenue'), 'value' => $data['revenue']['amount'], 'color' => '#80bc00'],
                                        ['label' => __('Fees'), 'value' => $data['revenue']['fee'], 'color' => '#fcb800'],
                                        ['label' => __('Withdrawals'), 'value' => $data['revenue']['withdrawal'], 'color' => '#fc6b00'],
                                    ]) }}"
                            ></revenue-chart>
                        </div>

                        <div class="row mt-4">
                            <x-core::datagrid.item class="col-6 mb-2">
                                <x-slot:title>
                                    <x-core::icon name="ti ti-wallet"></x-core::icon>
                                    {{ __('Earnings') }}
                                </x-slot:title>
                                {{ format_price($data['revenue']['sub_amount']) }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item class="col-6 mb-2">
                                <x-slot:title>
                                    {{ __('Revenue') }}
                                </x-slot:title>
                                {{ format_price($data['revenue']['sub_amount'] - $data['revenue']['fee']) }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item class="col-6">
                                <x-slot:title>
                                        <span
                                            data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Includes Completed, Pending, and Processing statuses') }}"
                                        >
                                            {{ __('Withdrawals') }}
                                        </span>
                                </x-slot:title>
                                {{ format_price($data['revenue']['withdrawal']) }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item class="col-6">
                                <x-slot:title>
                                    {{ __('Balance') }}
                                </x-slot:title>
                                {{ format_price(auth('customer')->user()->balance) }}
                            </x-core::datagrid.item>
                        </div>

                        <div class="mt-4">
                            <a
                                href="{{ route('car-rentals.vendor.withdrawals.create') }}"
                                class="btn btn-primary"
                            >
                                {{ __('Withdraw') }}
                            </a>
                            <a
                                href="{{ route('car-rentals.vendor.withdrawals.index') }}"
                                class="btn btn-info"
                            >
                                {{ __('Withdrawal History') }}
                            </a>
                        </div>
                    </x-core::card.body>
                </x-core::card>
            </div>
        </div>
    @endif
</div>
