@php
    $isShowMap = $shortcode->show_map && $shortcode->map_address;
@endphp

<section class="shortcode-contact-form box-section box-contact-form background-body">
    <div class="container">
        <div class="row">
            <div @class(['mb-30', 'col-lg-6' => $isShowMap, 'col-12' => ! $isShowMap])>
                @if ($title = $shortcode->title)
                    <h2 class="shortcode-title mb-25">{!! BaseHelper::clean($title) !!}</h2>
                @endif
                <div class="form-contact">
                    {!! $form
                        ->setFormInputClass('form-control')
                        ->setFormInputWrapperClass('form-group')
                        ->setFormLabelClass('text-sm-medium neutral-1000')
                        ->modify(
                            'submit', 'submit', ['attr' => ['class' => 'btn btn-book'], 'label' => $shortcode->button_label ?: __('Send Message')], true)
                        ->renderForm()
                    !!}
                </div>
            </div>

            @if ($isShowMap)
                <div class="col-lg-6 mb-30">
                    <div class="ps-lg-5">
                        @if($mapTitle = $shortcode->map_title)
                            <h4 class="neutral-1000">{!! BaseHelper::clean($mapTitle) !!}</h4>
                        @endif
                        <p class="neutral-500 mb-30">{!! BaseHelper::clean($shortcode->map_address) !!}</p>
                        <iframe class="h-520 rounded-3" src="https://maps.google.com/maps?q={{ addslashes($shortcode->map_address) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="650" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
