<div class="card h-100">
    <div class="card-header">
        <h3 class="card-title">{{ trans('plugins/car-rentals::booking-reports.recent_customers') }}</h3>
    </div>
    <div class="card-body">
        @if(count($recentCustomers) > 0)
            <div class="list-group list-group-flush">
                @foreach($recentCustomers as $customer)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-md" style="background-color: #206bc4; color: #fff;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="font-weight-medium">
                                            <a href="{{ route('car-rentals.customers.edit', $customer->id) }}" class="text-reset">{{ $customer->name }}</a>
                                        </div>
                                        <div class="text-muted small">{{ $customer->email }}</div>
                                    </div>
                                    <div>
                                        <div class="badge bg-primary">
                                            {{ $customer->bookings_count }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between text-muted mt-1">
                                    <div class="d-flex align-items-center">
                                        <x-core::icon name="ti ti-calendar" class="me-1" size="sm" />
                                        <span class="small">{{ $customer->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <x-core::icon name="ti ti-currency-dollar" class="me-1" size="sm" />
                                        <span class="small">{{ format_price($customer->total_spent) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                <div class="empty-img">
                    <x-core::icon name="ti ti-users" class="text-muted" style="width: 6rem; height: 6rem; opacity: 0.2;" />
                </div>
                <p class="empty-title">{{ trans('plugins/car-rentals::booking-reports.no_customers_data') }}</p>
                <p class="empty-subtitle text-muted">
                    {{ trans('plugins/car-rentals::booking-reports.no_customers_data_description') }}
                </p>
            </div>
        @endif
    </div>
</div>
