@if (($quantity = Arr::get($config, 'quantity')) > 0)
    <div class="box-contactus">
        <a class="d-flex mb-4" href="{{ BaseHelper::getHomepageUrl() }}">
            @if ($logo = Arr::get($config, 'logo') ?: theme_option('logo'))
                {{ RvMedia::image($logo, theme_option('site_title'), attributes: ['height' => 32]) }}
            @endif
        </a>
        <div class="contact-info">
            @foreach(range(1, $quantity) as $i)
                @continue(Arr::get($config, "title_$i"))

                @if ($description = Arr::get($config, "description_$i"))
                    <div class="contact-item-wrapper text-md neutral-1000 mb-3">
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
@endif
