@extends(CarRentalsHelper::viewPath('customers.layouts.master'))

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="section-content">
                @include('plugins/car-rentals::themes.customers.bookings.booking-info')
            </div>
        </div>
    </div>
@endsection
