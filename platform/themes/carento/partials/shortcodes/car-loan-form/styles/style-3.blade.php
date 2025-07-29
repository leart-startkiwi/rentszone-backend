<section class="box-cta-7 background-body shortcode-car-loan-form shortcode-car-loan-form-style-3">
    <div class="container position-relative z-1">
        <div class="bg-shape"
            @style([
            "--background-image: url($backgroundImage) no-repeat 50%;" => $backgroundImage,
        ])
        ></div>
        <div class="row position-relative z-1">
            <div class="col-lg-6 p-md-5 p-4">
                <div class="background-card p-md-5 p-4 rounded-3 my-3">
                    @if($formTitle = $shortcode->form_title)
                        <h5 class="neutral-1000 mb-2">{!! BaseHelper::clean($formTitle) !!}</h5>
                    @endif

                    @if ($formDescription = $shortcode->form_description)
                        <p class="text-sm-medium neutral-500 mb-25">{!! BaseHelper::clean($formDescription) !!}</p>
                    @endif

                    {!! Theme::partial('shortcodes.car-loan-form.form', compact('shortcode', 'currency')) !!}
                </div>
            </div>
        </div>
    </div>
</section>
