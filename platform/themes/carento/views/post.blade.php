@use(Theme\Carento\Support\ThemeHelper)

@php
    Theme::set('breadcrumbs', false);
    Theme::layout('full-width');
    $blogSidebar = dynamic_sidebar('blog_sidebar');
@endphp

{!! apply_filters('ads_render', null, 'post_before', ['class' => 'mb-2']) !!}

<div class="post-detail-page">
    <div class="page-header pt-30 background-body">
        <div class="custom-container position-relative mx-auto">
            <div class="bg-overlay rounded-12 overflow-hidden">
                {{ RvMedia::image($post->image, $post->name, attributes: ['class' => 'w-100 h-100 rounded-12 img-banner']) }}
            </div>
            <div class="container position-absolute z-1 top-50 start-50 translate-middle d-none d-lg-block">
                @if ($category = $post->firstCategory)
                    <a href="{{ $category->url }}"><span class="btn btn-label-tag background-3">{{ $category->name }}</span></a>
                @endif
                <h2 class="text-white py-3  w-75 truncate-3-custom" title="{{ $post->name }}">{{ $post->name }}</h2>
                <div class="card-meta-user">
                    @if (ThemeHelper::isShowPostMeta('detail', 'author', true) && ($author = $post->author))
                        <div class="box-author-small">
                            {{ RvMedia::image($author->avatar_url, $author->name, attributes: ['class' => 'border-0']) }}
                            <p class="text-sm-bold">{{ $author->name }}</p>
                        </div>
                    @endif
                    <div class="card-meta gap-2 d-flex">
                        @if (ThemeHelper::isShowPostMeta('detail', 'published_date', true))
                            <span class="post-date text-white">{{ Theme::formatDate($post->created_at) }}</span>
                        @endif

                        @if (ThemeHelper::isShowPostMeta('list', 'views_count', true))
                            {!! Theme::partial('blog.post-meta.views-count', ['post' => $post, 'wrapperClass' => 'text-white post-time']) !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="background-body breadcrumbs position-absolute z-1 top-100 start-50 translate-middle px-3 py-2 rounded-12 border gap-3 d-none d-md-flex w-md-75">
                @foreach (Theme::breadcrumb()->getCrumbs() as $crumb)
                    @if (! $loop->last)

                        <a href="{{ $crumb['url'] }}" title="{{ $crumb['label'] }}" class="neutral-700 text-md-medium item">{{ $crumb['label'] }}</a>
                        <span>
                    <img src="{{ Theme::asset()->url('images/icons/arrow-right.svg') }}" alt="Icon" />
                </span>
                    @else
                        <span class="neutral-1000 text-md-bold last-item">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <section class="box-section background-body">
        <div class="container d-block d-lg-none mt-3">
            @if ($category = $post->firstCategory)
                <a href="{{ $category->url }}"><span class="btn btn-label-tag background-3">{{ $category->name }}</span></a>
            @endif
            <h2 class="py-3">{{ $post->name }}</h2>
            <div class="card-meta-user">
                @if (ThemeHelper::isShowPostMeta('detail', 'author', true) && ($author = $post->author))
                    <div class="box-author-small">
                        {{ RvMedia::image($author->avatar_url, $author->name, attributes: ['class' => 'border-0']) }}
                        <p class="text-sm-bold neutral-1000">{{ $author->name }}</p>
                    </div>
                @endif

                <div class="card-meta gap-2 d-flex">
                    @if (ThemeHelper::isShowPostMeta('detail', 'published_date', true))
                        <span class="post-date">{{ Theme::formatDate($post->created_at) }}</span>
                    @endif

                    @if (ThemeHelper::isShowPostMeta('list', 'views_count', true))
                        {!! Theme::partial('blog.post-meta.views-count', ['post' => $post, 'wrapperClass' => ' post-time']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="section-box background-body pt-96">
                <div class="container">
                    <div class="row">
                        <div @class(['mb-35', 'col-lg-8' => $blogSidebar, 'col-lg-12' => ! $blogSidebar])>
                            <div class="box-content-detail-blog">
                                <div class="box-content-info-detail mt-0 pt-0">
                                    @if ($description = $post->description)
                                        <p class="text-xl-medium mb-20 neutral-1000">{{ $description }}</p>
                                    @endif

                                    <div class="content-detail-post">
                                        {!! BaseHelper::clean($post->content) !!}
                                    </div>
                                    <div class="footer-post-tags mb-50">
                                        @if ($tags = $post->tags)
                                            <div class="box-tags">
                                                @foreach($tags as $tag)
                                                    <a class="btn btn-tag" href="{{ $tag->url }}">{{ $tag->name }}</a>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if ($socials = \Botble\Theme\Supports\ThemeSupport::getSocialSharingButtons($post->url, SeoHelper::getDescription()))
                                            <div class="box-share">
                                                <div class="d-flex align-items-center justify-content-center justify-content-md-end flex-wrap">
                                                    <p class="text-lg-bold neutral-1000 d-inline-block mr-10 mb-0">{{ __('Share this:') }}</p>
                                                    <div class="box-socials d-inline-block d-flex gap-2">
                                                        @foreach($socials as $social)
                                                            @php
                                                                $name = Arr::get($social, 'name');
                                                                $backgroundColor = Arr::get($social, 'background_color');
                                                                $color = Arr::get($social, 'color');
                                                            @endphp

                                                            <a
                                                                class="icon-shape icon-md rounded-circle border"
                                                                aria-label="{{ __('Share on :social', ['social' => $name]) }}"
                                                                @style(["background-color: {$backgroundColor}" => $backgroundColor, "color: {$color}" => $color])
                                                                href="{{ Arr::get($social, 'url') }}"
                                                                target="_blank"
                                                            >
                                                                {!! Arr::get($social, 'icon') !!}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="post-comment-wrapper">
                                        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $post) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($blogSidebar)
                            <div class="col-lg-4">
                                {!! $blogSidebar !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{!! apply_filters('ads_render', null, 'post_after', ['class' => 'mt-2']) !!}
