<div class="row">
    @forelse($cars as $car)
        @php
            $carUrl = $car->url;

            if (($startDate = request()->input('rental_start_date')) && ($endDate = request()->input('rental_end_date'))) {
                $carUrl = $car->url . '?rental_start_date=' . $startDate . '&rental_end_date=' . $endDate;
            }
        @endphp

        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div>
                <div class="archive-top">
                    <a href="{{ $car->url }}" class="images-group">
                        <div class="images-style">
                            {{ RvMedia::image($car->image, $car->name, 'thumb') }}
                        </div>
                        <div class="top">
                            <div class="d-flex gap-8">
                                {!! $car->status->toHtml() !!}
                            </div>
                        </div>
                        @if ($car->category)
                            <div class="bottom">
                                <span class="flag-tag style-2">{{ $car->category->name }}</span>
                            </div>
                        @endif
                    </a>
                    <div class="content">
                        <div>
                            <a href="{{ $car->url }}" class="link">{{ $car->name }}</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @empty
        <div class="text-center text-muted"> {{ __('No Car') }}</div>
    @endforelse
</div>
