@extends(CarRentalsHelper::viewPath('customers.layouts.master'))

@section('content')
    @php
        $customer = auth('customer')->user();
        $totalBookings = \Botble\CarRentals\Models\Booking::query()->where('customer_id', $customer->id)->count();
        $totalReviews = \Botble\CarRentals\Models\CarReview::query()->where('customer_id', $customer->id)->count();
        $recentBookings = \Botble\CarRentals\Models\Booking::query()
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    @endphp

    <!-- Welcome Section -->
    <div class="overview-welcome mb-4">
        <div class="d-flex align-items-center">
            <div class="overview-avatar me-3">
                <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="rounded-circle">
            </div>
            <div>
                <h4 class="mb-1">{{ __('Welcome back') }}, {{ $customer->name }}!</h4>
                <p class="text-muted mb-0">{{ __('Here\'s an overview of your account and recent activity') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="overview-stats mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="overview-stat-card bg-primary bg-opacity-10 border-start border-5 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $totalBookings }}</h2>
                            <p class="mb-0 text-muted">{{ __('Total Bookings') }}</p>
                        </div>
                        <div class="overview-stat-icon">
                            <x-core::icon name="ti ti-calendar" class="text-primary" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="overview-stat-card bg-warning bg-opacity-10 border-start border-5 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $totalReviews }}</h2>
                            <p class="mb-0 text-muted">{{ __('Total Reviews') }}</p>
                        </div>
                        <div class="overview-stat-icon">
                            <x-core::icon name="ti ti-star" class="text-warning" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="overview-stat-card bg-success bg-opacity-10 border-start border-5 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $customer->created_at->diffForHumans(null, true) }}</h2>
                            <p class="mb-0 text-muted">{{ __('Member Since') }}</p>
                        </div>
                        <div class="overview-stat-icon">
                            <x-core::icon name="ti ti-user" class="text-success" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Personal Information Section -->
        <div class="col-lg-6">
            <div class="overview-section">
                <div class="overview-section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                        <a href="{{ route('customer.profile') }}" class="btn btn-sm btn-outline-primary">
                            <x-core::icon name="ti ti-edit" class="me-1" />
                            {{ __('Edit') }}
                        </a>
                    </div>
                </div>
                <div class="overview-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="overview-info-item">
                                <span class="overview-info-label">{{ __('Name') }}</span>
                                <span class="overview-info-value">{{ $customer->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="overview-info-item">
                                <span class="overview-info-label">{{ __('Email') }}</span>
                                <span class="overview-info-value">{{ $customer->email }}</span>
                            </div>
                        </div>
                        @if ($customer->phone)
                            <div class="col-md-6">
                                <div class="overview-info-item">
                                    <span class="overview-info-label">{{ __('Phone') }}</span>
                                    <span class="overview-info-value">{{ $customer->phone }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="overview-info-item">
                                <span class="overview-info-label">{{ __('Member Since') }}</span>
                                <span class="overview-info-value">{{ $customer->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="col-lg-6">
            <div class="overview-section">
                <div class="overview-section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Recent Bookings') }}</h5>
                        <a href="{{ route('customer.bookings') }}" class="btn btn-sm btn-outline-primary">
                            <x-core::icon name="ti ti-eye" class="me-1" />
                            {{ __('View All') }}
                        </a>
                    </div>
                </div>
                <div class="overview-section-body">
                    @if ($recentBookings->isNotEmpty())
                        <div class="overview-recent-list">
                            @foreach ($recentBookings as $booking)
                                <div class="overview-recent-item">
                                    <div class="d-flex align-items-center">
                                        <div class="overview-recent-icon me-3">
                                            <span class="badge bg-{{ $booking->status->getColor() }}">
                                                <x-core::icon name="ti ti-calendar" />
                                            </span>
                                        </div>
                                        <div class="overview-recent-content">
                                            <h6 class="mb-1">{{ $booking->car->name }}</h6>
                                            <p class="text-muted small mb-0">
                                                {{ $booking->created_at->format('M d, Y') }} ·
                                                <span class="badge bg-{{ $booking->status->getColor() }} bg-opacity-10 text-{{ $booking->status->getColor() }}">
                                                    {{ $booking->status->label() }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="{{ route('customer.bookings.show', $booking->transaction_id) }}" class="btn btn-sm btn-link">
                                                <x-core::icon name="ti ti-arrow-right" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="overview-empty-state">
                            <div class="text-center py-4">
                                <x-core::icon name="ti ti-calendar-off" size="lg" class="text-muted mb-3" />
                                <h6>{{ __('No Bookings Yet') }}</h6>
                                <p class="text-muted small mb-3">{{ __("You haven't made any bookings yet.") }}</p>
                                <a href="{{ route('public.cars') }}" class="btn btn-sm btn-primary">
                                    <x-core::icon name="ti ti-car" class="me-1" />
                                    {{ __('Explore Cars') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
