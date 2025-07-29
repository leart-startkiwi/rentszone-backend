<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('plugins/car-rentals::booking-reports.top_performing_cars') }}</h3>
    </div>
    <div class="card-body">
        @if(count($topCars) > 0)
            <div class="row g-3">
                @foreach($topCars as $car)
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar avatar-md" style="background-image: url({{ RvMedia::getImageUrl($car->image, 'thumb', false, RvMedia::getDefaultImage()) }})"></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <a href="{{ route('car-rentals.cars.edit', $car->id) }}" class="text-reset">{{ $car->name }}</a>
                                        </div>
                                        @if($car->type)
                                            <div class="text-muted small">{{ $car->type->name }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <div class="text-muted d-flex align-items-center">
                                            <x-core::icon name="ti ti-calendar-event" class="me-1" size="sm" />
                                            <span class="small">{{ $car->bookings_count }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="text-muted d-flex align-items-center justify-content-end">
                                            <x-core::icon name="ti ti-currency-dollar" class="me-1" size="sm" />
                                            <span class="small">{{ format_price($car->revenue) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-primary" style="width: {{ ($car->bookings_count / $topCars->max('bookings_count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                <div class="empty-img">
                    <x-core::icon name="ti ti-car" class="text-muted" style="width: 6rem; height: 6rem; opacity: 0.2;" />
                </div>
                <p class="empty-title">{{ trans('plugins/car-rentals::booking-reports.no_cars_data') }}</p>
                <p class="empty-subtitle text-muted">
                    {{ trans('plugins/car-rentals::booking-reports.no_cars_data_description') }}
                </p>
            </div>
        @endif
    </div>
</div>
