@if($tabs)
    <div class="d-lg-flex gap-4">
        @foreach($tabs as $item)
            @continue(! ($content = Arr::get($item, 'content')))

            <p>{!! BaseHelper::clean($content) !!}</p>
        @endforeach
    </div>
@endif
