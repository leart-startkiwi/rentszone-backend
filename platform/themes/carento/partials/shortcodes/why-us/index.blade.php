@php
    $title = $shortcode->title;
    $subTitle = $shortcode->sub_title;
@endphp
<section class="shortcode-why-us section-box box-why-book-22 background-body">
    <div class="container">
        <div class="text-center wow fadeInUp">
            @if($subTitle)
                <p class="text-xl-medium shortcode-subtitle wow fadeInUp">{!! BaseHelper::clean($subTitle) !!}</p>
            @endif

            @if($title)
                <h2 class="heading-3 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
            @endif
        </div>
        <div class="row mt-40">
            @foreach($tabs as $tab)
                @continue(! $cardImage = Arr::get($tab, 'card_image'))
                @continue(! $cardTitle = Arr::get($tab, 'card_title'))
                @continue(! $cardContent = Arr::get($tab, 'card_content'))

                <div class="col-lg-3 col-sm-6">
                    <div class="card-why wow fadeIn" data-wow-delay="0.1s">
                        <div class="card-image">
                            {{ RvMedia::image($cardImage, $cardTitle, attributes: ['width' => 80]) }}
                        </div>
                        <div class="card-info">
                            <h6 class="text-xl-bold neutral-1000">{!! BaseHelper::clean($cardTitle) !!}</h6>
                            <p class="text-md-medium neutral-500">{!! BaseHelper::clean($cardContent) !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
