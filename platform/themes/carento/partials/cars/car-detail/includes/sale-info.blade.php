@if($car->is_for_sale)
    <div class="sale-info">
        <div class="head-sale-info">
            <p class="text-xl-bold neutral-1000 d-inline-block me-1">{{ __('Buy This Car') }}</p>
            @if($car->sale_status)
                <span class="car-sale-status text-center">
                    {!! $car->sale_status->toHtml() !!}
                </span>
            @endif
        </div>
        <div class="content-sale-info">
            <div class="car-price-section">
                <div><span class="text-md-medium neutral-500">{{ __('Price') }}: </span><h4 class="sale-price d-inline-block">{{ format_price($car->sale_price) }}</h4></div>
            </div>

            @if($car->condition)
                <div class="car-condition mt-3">
                    <p class="text-md-medium neutral-500">{{ __('Condition') }}: <span class="neutral-800">{{ $car->condition->label() }}</span></p>
                </div>
            @endif

            @if($car->tax && $car->tax->percentage)
                <div class="car-taxes mt-3">
                    <p class="text-md-medium neutral-500">{{ __('Tax') }}: <span class="neutral-800">{{ $car->tax->percentage }}%</span></p>
                </div>
            @endif

            @if($car->warranty_information)
                <div class="car-warranty mt-3">
                    <p class="text-md-medium neutral-500">{{ __('Warranty') }}: <span class="neutral-800">{{ $car->warranty_information }}</span></p>
                </div>
            @endif
        </div>
    </div>
@endif
