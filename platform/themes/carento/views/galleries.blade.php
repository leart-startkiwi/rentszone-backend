@php
    Theme::set('pageTitle', __('Galleries'));
@endphp

@if ($galleries->isNotEmpty())
    <div class="row gy-4 mt-5 mb-5">
        @foreach($galleries as $gallery)
            <div class="col-sm-6">
                <div class="post post-grid rounded bordered">
                    <div class="thumb top-rounded">
                        <a href="{{ $gallery->url }}" title="{{ $gallery->name }}">
                            <div class="inner">
                                {{ RvMedia::image($gallery->image, $gallery->title, 'medium') }}
                            </div>
                        </a>
                    </div>

                    <div class="details">
                        <div class="post-title mb-3 mt-3 h5">
                            <a class="truncate-custom truncate-2-custom" title="{{ $gallery->name }}" href="{{ $gallery->url }}">{{ $gallery->name }}</a>
                        </div>
                    </div>

                    <div class="post-bottom clearfix d-flex align-items-center">
                        <div class="more-button float-end">
                            <a title="{{ __('View') }}" href="{{ $gallery->url }}"><span class="icon-options"></span></a>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    @if ($galleries instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $galleries->total() > 0)
        <div class="text-center mt-30">
            {{ $galleries->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
        </div>
    @endif
@else
    <div class="mt-3 text-center">
        <p>{{ __('No galleries found.') }}</p>
    </div>
@endif
