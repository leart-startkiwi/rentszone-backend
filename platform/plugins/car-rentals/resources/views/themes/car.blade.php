<div class="container">
    <div class="row mb-3">
        <div class="col-lg-8">
            <h1> {{ $car->name }}</h1>

            {{ RvMedia::image($car->image, $car->name, attributes: ['class' => 'img-fluid']) }}
        </div>

        <div class="col-lg-4">
            <div class="card bg-white">
                <div class="card-header">
                    <div class="card-title">
                        {{ __('Booking Information')  }}
                    </div>
                </div>

                <div class="card-body">
                    {!! \Botble\CarRentals\Forms\Fronts\BookingForm::createFromArray([
                        'car_id' => $car->id,
                    ])->renderForm() !!}
                </div>
            </div>
        </div>
    </div>
</div>
