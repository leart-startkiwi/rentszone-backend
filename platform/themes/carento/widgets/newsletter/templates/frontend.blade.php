<div class="row align-items-center widget-newsletter">
    @if ($title = Arr::get($config, 'title'))
        <div class="col-lg-5 col-md-6 text-center text-md-start">
            <h5 class="color-white wow fadeInDown">{{ $title }}</h5>
        </div>
    @endif

    <div class="col-lg-7 col-md-6 text-center text-md-end mt-md-0 mt-4">
        <div class="d-flex align-items-center justify-content-center justify-content-md-end">
            {!! $form->renderForm() !!}
        </div>
    </div>
</div>
