@php
    $title = $shortcode->title;
    $description = $shortcode->description;
@endphp

<section class="shortcode-car-loan-form shortcode-car-loan-form-style-1 box-cta-2 background-body overflow-hidden">
    <div class="bg-shape top-50 start-50 translate-middle"
    @style([
        "--background-image: url($backgroundImage) no-repeat 50%;" => $backgroundImage,
    ])
    ></div>
    <div class="container position-relative z-1">
        <div class="row ">
            @if($title || $description)
                <div class="col-lg-5 pe-lg-5 ">
                    @if($title)
                        <h2 class="heading-3 text-white wow fadeInDown shortcode-title">{!! BaseHelper::clean($title) !!}</h2>
                    @endif

                    @if($description)
                        <p class="text-lg-medium text-white wow fadeInUp">{!! BaseHelper::clean($description) !!}</p>
                    @endif
                </div>
            @endif
            <div class="col-lg-6 offset-lg-1">
                <div class="mb-30 background-card p-md-5 p-4 rounded-3 mt-lg-0 mt-30 wow fadeIn">
                    @if($formTitle = $shortcode->form_title)
                        <h5 class="neutral-1000 mb-2">{!! BaseHelper::clean($formTitle) !!}</h5>
                    @endif

                    @if($formDescription = $shortcode->form_description)
                        <p class="text-sm-medium neutral-500 mb-25">{!! BaseHelper::clean($formDescription) !!}</p>
                    @endif

                    {!! Theme::partial('shortcodes.car-loan-form.form', compact('shortcode', 'currency')) !!}
                </div>
            </div>
        </div>
    </div>
</section>
