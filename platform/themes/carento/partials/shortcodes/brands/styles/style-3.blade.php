@php
    $alphabets = [];

    $makes = $makes->sortBy('name');

    foreach ($makes as $make) {
        $alphabets[] = substr($make->name, 0, 1);
    }

    $alphabets = array_unique($alphabets);

    sort($alphabets);
@endphp

<div class="shortcode-brands brand-style-3 pb-70">
    <div class="container filter-brands-by-alphabet">
        <div class="alphabet-grid mb-3 text-center">
            @if ($title)
                <h2 class="heading-3 mb-3 shortcode-title wow fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
            @endif

            <div class="d-flex flex-wrap justify-content-center">
                @foreach($alphabets as $alphabet)
                    <button data-bb-toggle="filter-brands" data-bb-value="{{ $alphabet }}" class="letter-btn">{{ $alphabet }}</button>
                @endforeach
            </div>
        </div>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @foreach($makes as $make)
                <div class="col" data-bb-toggle="brand-item" data-bb-value="{{ substr($make->name, 0, 1) }}">
                    <div class="brand-item text-center">
                        <a href="{{ $make->url }}">
                            <span title="{{ $make->name }}">
                            {{ RvMedia::image($make->logo, $make->name, attributes: ['class' => 'light-mode']) }}
                                {{ RvMedia::image($make->getMetaData('logo_dark', true), $make->name, attributes: ['class' => 'dark-mode']) }}
                        </span>
                        </a>
                    </div>
                    <h6 class="mt-3 text-center">
                        <a href="{{ $make->url }}"><span title="{{ $make->name }}">{!! BaseHelper::clean($make->name) !!}</span></a>
                    </h6>
                </div>
            @endforeach
        </div>
    </div>
</div>
