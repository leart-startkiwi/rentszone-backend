<section class="box-cta-5 background-body shortcode-car-loan-form shortcode-car-loan-form-style-2">
    <div class="bg-shape"
        @style([
            "--background-image: url($backgroundImage) no-repeat 50%;" => $backgroundImage,
        ])
    ></div>
    <div class="container position-relative z-1 pt-100 pb-100">
        <div class="row ">
            <div class="col-lg-6 order-last order-lg-first">
                <div class="mb-30 background-card p-md-5 p-4 rounded-3 mt-lg-0 mt-30">
                    @if($formTitle = $shortcode->form_title)
                        <h5 class="neutral-1000 mb-2">{!! BaseHelper::clean($formTitle) !!}</h5>
                    @endif

                    @if ($formDescription = $shortcode->form_description)
                        <p class="text-sm-medium neutral-500 mb-25">{!! BaseHelper::clean($formDescription) !!}</p>
                    @endif

                    {!! Theme::partial('shortcodes.car-loan-form.form', compact('shortcode', 'currency')) !!}
                </div>
            </div>
            <div class="col-lg-6 order-first order-lg-last wow fadeInUp pt-lg-60">
                <div class="ps-lg-5">
                    @if($title = $shortcode->title)
                        <h2 class="heading-3 text-white mb-3">{!! BaseHelper::clean($title) !!}</h2>
                    @endif

                    @if ($description = $shortcode->description)
                        <p class="text-lg-medium text-white">{!! BaseHelper::clean($description) !!}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
