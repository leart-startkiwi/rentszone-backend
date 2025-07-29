@if (($quantity = Arr::get($config, 'quantity')) > 0)
    <div class="text-header-info widget-contact-information">
        @foreach(range(1, $quantity) as $i)
            @continue(! ($title = Arr::get($config, "title_$i")))

            <a class="phone-head text-white" href="{{ Arr::get($config, "url_$i") }}">
                @if ($iconImage = Arr::get($config, "icon_image_$i"))
                    {{ RvMedia::image($iconImage, $title, attributes: ['width' => 24, 'height' => 24]) }}
                @elseif( $icon = Arr::get($config, "icon_$i"))
                    {!! BaseHelper::renderIcon($icon) !!}
                @endif
                <span class="d-none d-lg-inline-block" dir="ltr">{{ $title }}</span>
            </a>
        @endforeach
    </div>
@endif
