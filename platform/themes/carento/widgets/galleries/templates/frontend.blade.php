@if($galleries->isNotEmpty())
    <div class="box-sidebar-border">
        @if ($title = Arr::get($config, 'title'))
            <div class="box-head-sidebar">
                <p class="text-xl-bold neutral-1000">{{ $title }}</p>
            </div>
        @endif

        <div class="box-content-sidebar">
            <ul class="list-photo-col-3">
                @foreach($galleries as $gallery)
                    <li>
                        <a href="{{ $gallery->url }}">
                            {{ RvMedia::image($gallery->image, $gallery->name, 'thumb') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

