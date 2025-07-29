<div class="card-facilities {{ $cssClass ?? '' }}">
    @if ($car->mileage)
        <p class="card-miles text-md-medium">{{ __(':number miles', ['number' => number_format($car->mileage)]) }}</p>
    @endif

    @if ($car->transmission)
        <p class="card-gear text-md-medium">{{ $car->transmission->name }}</p>
    @endif

    @if ($car->fuel)
        <p class="card-fuel text-md-medium">{{ $car->fuel->name }}</p>
    @endif

    @if ($car->number_of_seats)
        <p class="card-seat text-md-medium">{{ __(':number seats', ['number' => $car->number_of_seats]) }}</p>
    @endif
</div>
