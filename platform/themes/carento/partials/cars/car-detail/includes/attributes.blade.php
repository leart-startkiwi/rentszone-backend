<div class="box-feature-car">
    <div class="list-feature-car">
        @if($mileage = $car->mileage)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    <div class="feature-image">
                        <img src="{{ Theme::asset()->url('images/icons/km.svg') }}" alt="{{ __('Mileage') }}">
                    </div>
                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{{ __(':number miles', ['number' => $mileage]) }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($fuel = $car->fuel)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    @if($fuelIcon = $fuel->icon)
                        <div class="feature-image">
                            {{ RvMedia::image($fuelIcon, $fuel->name, attributes: ['width' => 20, 'height' => 20]) }}
                        </div>
                    @endif


                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{!! BaseHelper::clean($fuel->name) !!}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($transmission = $car->transmission)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    @if ($icon = $transmission->icon)
                        <div class="feature-image">
                            {{ RvMedia::image($icon, $transmission->name, attributes: ['width' => 20, 'height' => 20]) }}
                        </div>
                    @endif

                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{!! BaseHelper::clean($transmission->name) !!}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($numberSeats = $car->number_of_seats)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    <div class="feature-image">
                        <img src="{{ Theme::asset()->url('images/icons/seat.svg') }}" alt="{{ __('Seats') }}">
                    </div>
                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{{ __(':number Seats', ['number' => $numberSeats]) }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($type = $car->type)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    @if($iconType = $type->icon)
                        <div class="feature-image">
                            {{ RvMedia::image($iconType, $type->name, attributes: ['width' => 20, 'height' => 20]) }}
                        </div>
                    @endif

                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{!! BaseHelper::clean($type->name) !!}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($numberDoors = $car->number_of_doors)
            <div class="item-feature-car w-md-25">
                <div class="item-feature-car-inner">
                    <div class="feature-image">
                        <img src="{{ Theme::asset()->url('images/icons/door.svg') }}" alt="{{ __('Doors') }}">
                    </div>

                    <div class="feature-info">
                        <p class="text-md-medium neutral-1000">{{ __(':number Doors', ['number' => $numberDoors]) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
