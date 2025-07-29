@if (($quantity = Arr::get($config, 'quantity')) > 0)
    <div class="col-md-3 col-sm-12 footer-1 widget-site-information">
        <div class="mb-20">
            <a class="d-flex" href="{{ BaseHelper::getHomepageUrl() }}">
                @if ($logo = Arr::get($config, 'logo') ?: theme_option('logo'))
                    {{ RvMedia::image($logo, theme_option('site_title')) }}
                @endif
            </a>
            <div class="box-info-contact mt-0">
                <div class="contact-list mb-30">
                    @foreach(range(1, $quantity) as $i)
                        @continue(Arr::get($config, "title_$i"))

                        @if ($description = Arr::get($config, "description_$i"))
                            <div class="contact-item-wrapper text-md neutral-400">
                                @php
                                    $iconImage = Arr::get($config, "icon_image_$i");
                                    $icon = Arr::get($config, "icon_$i");
                                @endphp

                                @if ($iconImage)
                                    {{ RvMedia::image($iconImage, 'icon', attributes: ['class' => 'icon']) }}
                                @elseif ($icon)
                                    {!! BaseHelper::renderIcon($icon, attributes: ['class' => 'icon']) !!}
                                @endif

                                <span>
                                    @if (filter_var($description, FILTER_VALIDATE_EMAIL))
                                        {!! Html::mailto($description) !!}
                                    @else
                                        {!! BaseHelper::clean($description) !!}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @foreach(range(1, $quantity) as $i)
                @continue(! $title = Arr::get($config, "title_$i"))
                <div class="box-need-help">
                    <p class="need-help text-md-medium mb-5 heading-5">
                        @php
                            $iconImage = Arr::get($config, "icon_image_$i");
                            $icon = Arr::get($config, "icon_$i");
                        @endphp

                        @if ($iconImage)
                            {{ RvMedia::image($iconImage, 'icon', attributes: ['class' => 'icon neutral-400']) }}
                        @elseif ($icon)
                            {!! BaseHelper::renderIcon($icon, attributes: ['class' => 'icon neutral-400']) !!}
                        @endif
                        @if (filter_var($title, FILTER_VALIDATE_EMAIL))
                            {!! Html::mailto($title) !!}
                        @else
                            {{ $title }}
                        @endif
                    </p>

                    @if ($description = Arr::get($config, "description_$i"))
                        <div class="heading-6 phone-support" dir="ltr">
                            {!! BaseHelper::clean($description) !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
