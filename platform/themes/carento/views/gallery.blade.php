@use(Botble\Theme\Supports\ThemeSupport)

@php
    $sidebar = dynamic_sidebar('primary_sidebar');
    Theme::set('pageTitle', $gallery->name);
    Theme::set('pageDescription', $gallery->description);
@endphp

<div class="post post-single mt-5 mb-3">
    <div class="post-content clearfix ck-content">
        {!! BaseHelper::clean($gallery->description ) !!}
    </div>

    <div id="list-photo" class="post-photos row g-4">
        @foreach (gallery_meta_data($gallery) as $image)
            @continue(! $image)

            <div
                class="gallery-items col-lg-4 col-md-6 col-12"
                data-src="{{ RvMedia::getImageUrl($imageUrl = Arr::get($image, 'img')) }}"
                data-sub-html="{{ $description = BaseHelper::clean(Arr::get($image, 'description')) }}"
            >
                <div class="photo-item">
                    <div class="thumb img-transition-scale">
                        <a href="{{ $description }}">
                            {{ RvMedia::image($imageUrl, $description, 'medium-rectangle') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="gallery-comment-wrapper">
    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $gallery) !!}
</div>
