@php
    $category ??= null;
    $post ??= null;

    if (! $category && $post) {
        $category = $post->firstCategory;
    }
@endphp

@if ($category)
    <a class="btn btn-label-tag background-3" href="{{ $category->url }}">{{ $category->name }}</a>
@endif
