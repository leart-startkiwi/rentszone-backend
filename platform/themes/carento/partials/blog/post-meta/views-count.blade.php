@php
    $view = $post->views ?? null;
    $wrapperClass ??= null;
@endphp

<span @class(['post-views', $wrapperClass])>
    @switch($view)
        @case(0)
            {{ __('No Views') }}
            @break
        @case(1)
            {{ __('1 View') }}
            @break
        @default
            {{ __(':count Views', ['count' => number_format($view)]) }}
            @break
    @endswitch
</span>
