<div class="box-form-reviews">
    @if(auth('customer')->check())
        {!! \Botble\CarRentals\Forms\Fronts\ReviewForm::createFromArray(['car' => $car])->renderForm() !!}
    @else
        <div class="alert alert-warning">
            {!! __('Please :login to leave a review.', [
                'login' => '<a href="' . route('customer.login') . '">' . __('login') . '</a>',
            ]) !!}
        </div>
    @endif
</div>
