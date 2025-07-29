<div class="col-md-2 col-xs-6 footer-3">
    @if ($title = Arr::get($config, 'name'))
        <p class="widget-title text-linear-3">{{ $title }}</p>
    @endif
    <ul class="menu-footer">
        @foreach($items as $item)
            @if (($label = $item->label) && ($url = $item->url))
                <li><a @if($item->is_open_new_tab) target="_blank" @endif  @if($attrs = $item->attributes) {{ $attrs }} @endif href="{{ $url }}">{!! BaseHelper::clean($label) !!}</a></li>
            @endif
        @endforeach
    </ul>
</div>
