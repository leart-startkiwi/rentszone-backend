@extends(CarRentalsHelper::viewPath('customers.layouts.master'))

@section('content')
    <div class="section-content">
        <div class="mb-4">

            @if (count($bookings) > 0)
                <div class="row">
                    @foreach ($bookings as $booking)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="bb-customer-card">
                                @if ($booking->car->car->exists && ($car = $booking->car->car))
                                    <div class="bb-card-image">
                                        <a href="{{ $car->url }}" target="_blank">
                                            <img src="{{ RvMedia::getImageUrl($car->image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                                 alt="{{ $car->name }}">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="bb-card-meta">
                                            <div class="bb-card-date">
                                                <x-core::icon name="ti ti-calendar" />
                                                {{ $booking->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="bb-card-status">
                                                <span class="badge bg-{{ $booking->status->getColor() }}">
                                                    {{ $booking->status->label() }}
                                                </span>
                                            </div>
                                        </div>
                                        <h5 class="bb-card-title">
                                            <a href="{{ $car->url }}" target="_blank">{{ $car->name }}</a>
                                        </h5>
                                        <div class="bb-card-price">{{ format_price($booking->car->price) }}</div>
                                        <div class="bb-card-content">
                                            <strong>{{ __('Booking Period') }}:</strong>
                                            <div class="booking-period">
                                                <span class="booking-date">{{ $booking->car->rental_start_date->format('M d, Y') }}</span>
                                                <span class="booking-arrow">
                                                    <x-core::icon name="ti ti-arrow-right" />
                                                </span>
                                                <span class="booking-date">{{ $booking->car->rental_end_date->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bb-card-image">
                                        <img src="{{ RvMedia::getImageUrl($booking->car->car_image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                             alt="{{ $booking->car->name }}">
                                    </div>
                                    <div class="card-body">
                                        <div class="bb-card-meta">
                                            <div class="bb-card-date">
                                                <x-core::icon name="ti ti-calendar" />
                                                {{ $booking->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="bb-card-status">
                                                <span class="badge bg-{{ $booking->status->getColor() }}">
                                                    {{ $booking->status->label() }}
                                                </span>
                                            </div>
                                        </div>
                                        <h5 class="bb-card-title">{{ $booking->car->name }}</h5>
                                        <div class="bb-card-price">{{ format_price($booking->car->price) }}</div>
                                        <div class="bb-card-content">
                                            <strong>{{ __('Booking Period') }}:</strong>
                                            <div class="booking-period">
                                                <span class="booking-date">{{ $booking->car->rental_start_date->format('M d, Y') }}</span>
                                                <span class="booking-arrow">
                                                    <x-core::icon name="ti ti-arrow-right" />
                                                </span>
                                                <span class="booking-date">{{ $booking->car->rental_end_date->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="card-footer">
                                    <a class="btn btn-primary btn-sm w-100" href="{{ route('customer.bookings.show', $booking->transaction_id) }}">
                                        <x-core::icon name="ti ti-eye" class="me-1" />
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {!! $bookings->links(CarRentalsHelper::viewPath('partials.pagination')) !!}
                </div>
            @else
                <div class="bb-empty-state">
                    <x-core::icon name="ti ti-calendar-off" size="lg" />
                    <h4>{{ __('No Bookings Yet') }}</h4>
                    <p>{{ __("You haven't made any bookings yet. Start exploring our cars and book your first ride!") }}</p>
                    <div class="text-center">
                        <a href="{{ route('public.cars') }}" class="btn btn-primary d-inline-block">
                            <x-core::icon name="ti ti-car" class="me-1" />
                            {{ __('Explore Cars') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
