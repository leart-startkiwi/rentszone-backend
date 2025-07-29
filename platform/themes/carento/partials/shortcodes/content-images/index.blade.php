@if($tabs)
    @php
        $count = count($tabs);
    @endphp
    <div class="row mb-3">
        @foreach($tabs as $item)
            @continue(! ($image = Arr::get($item, 'image')))
            <div class="col-lg-6">
                {{ RvMedia::image($image, '', attributes: ['class' => 'w-100 h-100 img-banner']) }}
            </div>
        @endforeach
    </div>
@endif
