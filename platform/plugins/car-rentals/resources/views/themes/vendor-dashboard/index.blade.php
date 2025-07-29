@extends(CarRentalsHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    @if($totalCars == 0)
    <!-- No Cars Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white rounded-circle p-3 me-3 position-relative">
                                    <x-core::icon name="ti ti-car" class="text-primary" style="width: 2.5rem; height: 2.5rem;" />
                                    <span class="position-absolute" style="bottom: 8px; right: 8px;">
                                        <x-core::icon name="ti ti-plus" class="text-success bg-white rounded-circle" style="width: 1.2rem; height: 1.2rem;" />
                                    </span>
                                </div>
                                <h2 class="mb-0">{{ __('Welcome to Your Car Rental Dashboard!') }}</h2>
                            </div>
                            <p class="fs-5 mb-4">{{ __('You haven\'t added any cars to your fleet yet. Start your journey by adding your first car and begin earning revenue from rentals.') }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('car-rentals.vendor.cars.create') }}" class="btn btn-lg btn-light">
                                    <x-core::icon name="ti ti-plus" class="me-1" /> {{ __('Add Your First Car') }}
                                </a>
                                <a href="{{ route('car-rentals.vendor.settings.index') }}" class="btn btn-lg btn-outline-light">
                                    <x-core::icon name="ti ti-settings" class="me-1" /> {{ __('Complete Your Profile') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block text-center">
                            <x-core::icon name="ti ti-car" class="text-white opacity-25" style="width: 12rem; height: 12rem;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Overview Section -->
    <div class="row mb-4">
        <div class="col-12">
            <x-core::stat-widget class="shadow-sm row-cols-1 row-cols-sm-2 row-cols-md-4">
                <x-core::stat-widget.item
                    :label="__('Cars')"
                    :value="$totalCars"
                    icon="ti ti-car"
                    color="primary"
                />

                <x-core::stat-widget.item
                    :label="__('Bookings')"
                    :value="$totalBookings"
                    icon="ti ti-calendar-event"
                    color="success"
                />

                <x-core::stat-widget.item
                    :label="__('Revenue')"
                    :value="format_price($data['revenue']['amount'] ?? 0)"
                    icon="ti ti-wallet"
                    color="info"
                />

                <x-core::stat-widget.item
                    :label="__('Messages')"
                    :value="$totalMessages"
                    icon="ti ti-mail-check"
                    color="danger"
                />
            </x-core::stat-widget>
        </div>
    </div>

    <!-- Business Management Section -->
    <div class="row mb-4">
        <!-- Quick Actions Widget -->
        <div class="col-lg-3 col-md-4 mb-3">
            <x-core::card class="h-100 shadow-sm">
                <x-core::card.header class="bg-light">
                    <div>
                        <x-core::card.title>
                            <x-core::icon name="ti ti-bolt" class="me-1" />
                            {{ __('Quick Actions') }}
                        </x-core::card.title>
                    </div>
                </x-core::card.header>
                <x-core::card.body>
                    <div class="d-grid gap-2">
                        <a href="{{ route('car-rentals.vendor.cars.create') }}" class="btn btn-primary">
                            <x-core::icon name="ti ti-plus" /> {{ __('Add New Car') }}
                        </a>
                        <a href="{{ route('car-rentals.vendor.bookings.index') }}" class="btn btn-info">
                            <x-core::icon name="ti ti-calendar" /> {{ __('Manage Bookings') }}
                        </a>
                        <a href="{{ route('car-rentals.vendor.settings.index') }}" class="btn btn-secondary">
                            <x-core::icon name="ti ti-settings" /> {{ __('Account Settings') }}
                        </a>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>

        <!-- Maintenance Alerts Widget -->
        <div class="col-lg-4 col-md-8 mb-3">
            <x-core::card class="h-100 shadow-sm">
                <x-core::card.header class="bg-light">
                    <div>
                        <x-core::card.title>
                            <x-core::icon name="ti ti-alert-triangle" class="me-1" />
                            {{ __('Maintenance Alerts') }}
                        </x-core::card.title>
                    </div>
                </x-core::card.header>
                <x-core::card.body class="p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($data['maintenanceAlerts']) && count($data['maintenanceAlerts']) > 0)
                            @foreach($data['maintenanceAlerts'] as $alert)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1 text-truncate">
                                            <x-core::icon name="ti ti-car" class="me-1" />
                                            {{ $alert->car ? ($alert->car->name ?? $alert->car->license_plate ?? __('Car #:id', ['id' => $alert->car->id])) : __('Unknown Car') }}
                                        </h6>
                                        <span class="badge text-white bg-{{ $alert->priority == 'high' ? 'danger' : ($alert->priority == 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($alert->priority ?? __('low')) }}
                                        </span>
                                    </div>
                                    <p class="mb-1 small">{{ $alert->message ?? __('This car may need maintenance.') }}</p>
                                    <small class="text-muted">{{ __('Last maintenance') }}: {{ $alert->last_maintenance ? $alert->last_maintenance->format('M d, Y') : __('Never') }}</small>
                                </div>
                            @endforeach
                        @else
                            <div class="empty py-4">
                                <div class="empty-icon mb-3">
                                    <x-core::icon name="ti ti-circle-check" class="text-success" style="width: 2.5rem; height: 2.5rem;" />
                                </div>
                                <p class="empty-title h5">{{ __('No maintenance alerts') }}</p>
                                <p class="empty-subtitle text-muted">
                                    {{ __('All your cars are in good condition') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>

        <!-- Recent Reviews Widget -->
        <div class="col-lg-5 col-md-12 mb-3">
            <x-core::card class="h-100 shadow-sm">
                <x-core::card.header class="bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <x-core::card.title>
                            <x-core::icon name="ti ti-star" class="me-1" />
                            {{ __('Recent Reviews') }}
                        </x-core::card.title>
                    </div>
                    <a href="{{ route('car-rentals.vendor.reviews.index') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('View All') }}
                        <x-core::icon name="ti ti-arrow-right" />
                    </a>
                </x-core::card.header>
                <x-core::card.body class="p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($data['recentReviews']) && count($data['recentReviews']) > 0)
                            @foreach($data['recentReviews'] as $review)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1 text-truncate">
                                            @if($review->car)
                                                {{ $review->car->name ?? $review->car->license_plate ?? __('Car #:id', ['id' => $review->car->id]) }}
                                            @else
                                                {{ __('Unknown Car') }}
                                            @endif
                                        </h6>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ti {{ $i <= ($review->star ?? 0) ? 'ti-star-filled text-warning' : 'ti-star' }}" title="{{ __('Rating: :star out of 5', ['star' => $review->star ?? 0]) }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-1 small">{{ Str::limit($review->content ?? __('No content'), 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $review->customer ? $review->customer->name : __('Unknown Customer') }}</small>
                                        <small class="text-muted">{{ $review->created_at ? $review->created_at->diffForHumans() : __('Unknown time') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty py-4">
                                <div class="empty-icon mb-3">
                                    <x-core::icon name="ti ti-message-circle" class="text-muted" style="width: 2.5rem; height: 2.5rem;" />
                                </div>
                                <p class="empty-title h5">{{ __('No reviews yet') }}</p>
                                <p class="empty-subtitle text-muted">
                                    {{ __('Reviews will appear here when customers leave feedback') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>

    <!-- Performance Section -->
    <div class="row mb-4">
        <!-- Top Performing Cars Widget -->
        <div class="col-lg-8 col-md-7 mb-3">
            <x-core::card class="shadow-sm">
                <x-core::card.header class="bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <x-core::card.title>
                            <x-core::icon name="ti ti-chart-bar" class="me-1" />
                            {{ __('Top Performing Cars') }}
                        </x-core::card.title>
                    </div>
                    <a href="{{ route('car-rentals.vendor.cars.index') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('View All Cars') }}
                        <x-core::icon name="ti ti-arrow-right" />
                    </a>
                </x-core::card.header>
                <x-core::card.body class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Car') }}</th>
                                    <th class="text-center">{{ __('Bookings') }}</th>
                                    <th class="text-end">{{ __('Revenue') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['topCars']) && count($data['topCars']) > 0)
                                    @foreach($data['topCars'] as $car)
                                        <tr>
                                            <td>
                                                <a href="{{ route('car-rentals.vendor.cars.edit', $car->id) }}" class="text-decoration-none">
                                                    <strong>{{ $car->name ?? $car->license_plate ?? __('Car #:id', ['id' => $car->id]) }}</strong>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $car->bookings_count ?? 0 }}</span>
                                            </td>
                                            <td class="text-end">{{ format_price($car->revenue ?? 0) }}</td>
                                            <td class="text-center">
                                                @if(isset($car->status) && method_exists($car->status, 'toHtml'))
                                                    {!! $car->status->toHtml() !!}
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Unknown') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="empty">
                                                <div class="empty-icon mb-3">
                                                    <x-core::icon name="ti ti-car-off" class="text-muted" style="width: 3rem; height: 3rem;" />
                                                </div>
                                                <p class="empty-title h4">{{ __('No cars in your fleet yet') }}</p>
                                                <p class="empty-subtitle text-muted">
                                                    {{ __('Start by adding your first car to begin receiving bookings and earning revenue.') }}
                                                </p>
                                                <div class="empty-action mt-3">
                                                    <a href="{{ route('car-rentals.vendor.cars.create') }}" class="btn btn-primary">
                                                        <x-core::icon name="ti ti-plus" class="me-1" /> {{ __('Add Your First Car') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>

        <!-- Revenue Summary Widget -->
        <div class="col-lg-4 col-md-5 mb-3">
            <x-core::card class="shadow-sm h-100">
                <x-core::card.header class="bg-light">
                    <div>
                        <x-core::card.title>
                            <x-core::icon name="ti ti-report-money" class="me-1" />
                            {{ __('Revenue Summary') }}
                        </x-core::card.title>
                        <x-core::card.subtitle>
                            {{ __('Period: :label', ['label' => $data['predefinedRange'] ?? __('Last 30 days')]) }}
                        </x-core::card.subtitle>
                    </div>
                </x-core::card.header>
                <x-core::card.body>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">{{ __('Gross Earnings') }}</div>
                                <div class="h5 mb-0">{{ format_price($data['revenue']['sub_amount'] ?? 0) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">{{ __('Net Revenue') }}</div>
                                <div class="h5 mb-0">{{ format_price(($data['revenue']['sub_amount'] ?? 0) - ($data['revenue']['fee'] ?? 0)) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">{{ __('Platform Fees') }}</div>
                                <div class="h5 mb-0">{{ format_price($data['revenue']['fee'] ?? 0) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">{{ __('Current Balance') }}</div>
                                <div class="h5 mb-0">{{ format_price(auth('customer')->user()->balance ?? 0) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('car-rentals.vendor.withdrawals.create') }}" class="btn btn-primary">
                            <x-core::icon name="ti ti-cash" /> {{ __('Request Withdrawal') }}
                        </a>
                        <a href="{{ route('car-rentals.vendor.revenues.index') }}" class="btn btn-outline-secondary">
                            <x-core::icon name="ti ti-report" /> {{ __('View Detailed Reports') }}
                        </a>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@stop
