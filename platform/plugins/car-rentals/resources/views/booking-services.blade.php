<div class="booking-services-wrapper">
    <div class="row g-3">
        @foreach($services as $serviceId => $service)
            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox"
                        class="form-check-input"
                        id="service_{{ $serviceId }}"
                        name="services[]"
                        value="{{ $serviceId }}"
                        @if(in_array($serviceId, $selectedServices)) checked @endif
                    >
                    <label class="form-check-label" for="service_{{ $serviceId }}">
                        <strong>{{ $service['name'] }}</strong>
                        <span class="text-muted ms-2">
                            ({{ format_price($service['price']) }}
                            @if(isset($service['price_type']) && $service['price_type'] == 'per_day')
                                / {{ trans('plugins/car-rentals::booking.day') }}
                            @endif)
                        </span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>
