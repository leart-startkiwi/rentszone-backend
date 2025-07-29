<section class="box-section background-2 py-96 mt-40">
    <div class="container">
        <div class="text-center  mb-40">
            @if($title = $shortcode->title)
                <h2 class="heading-3 my-3 shortcode-title">{{ BaseHelper::clean($title) }}</h2>
            @endif
            @if($description = $shortcode->description)
                <p class="text-xl-medium neutral-500">{{ BaseHelper::clean($description) }}</p>
            @endif
        </div>
        <div class="row mt-60">
            @foreach($faqCategories as $faqCategory)
                @continue(! $faqCategoryName = $faqCategory->name)

                @php
                    $faqCategoryLogo = $faqCategory->getMetaData('logo', true);
                    $faqCategoryLogoDark = $faqCategory->getMetaData('logo_dark', true);
                @endphp

                <div class="col-lg-3 col-sm-6">
                    <div class="card-contact">
                        @if(!empty($faqCategoryLogo) || !empty($faqCategoryLogoDark))
                            <div class="card-image">
                                <div class="card-icon background-card border rounded-2 border-dark">
                                    {{ RvMedia::image($faqCategoryLogo, $faqCategoryName, attributes: ['class' => 'light-mode', 'style' => 'max-height: 50px; max-width: 100%;']) }}
                                    {{ RvMedia::image($faqCategoryLogoDark, $faqCategoryName, attributes: ['class' => 'dark-mode', 'style' => 'max-height: 50px; max-width: 100%;']) }}
                                </div>
                            </div>
                        @endif
                        <div class="card-info">
                            <div class="card-title">
                                <a class="title text-lg-bold" href="#">{!! BaseHelper::clean($faqCategoryName) !!}</a>
                                @if($description = $faqCategory->$description)
                                    <p class="text-xs-medium neutral-500">{!! BaseHelper::clean($description) !!}</p>
                                @endif
                            </div>
                            <div class="card-method-contact">
                                <a class="email text-md-bold" href="#">
                                    {{ __('Details') }}
                                    <svg class="svg-icon-arrow" width="16" height="16" viewbox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 15L15 8L8 1M15 8L1 8" stroke="" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
