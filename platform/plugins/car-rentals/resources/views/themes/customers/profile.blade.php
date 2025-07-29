@extends(CarRentalsHelper::viewPath('customers.layouts.master'))

@section('content')
    <div class="row g-4">
        {!! $form->renderForm() !!}
    </div>
@endsection
