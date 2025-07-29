@php
    $category ??= null;
    $post ??= null;

    if (! $category && $post) {
        $category = $post->firstCategory;
    }
@endphp

@if ($category)
    <label class="bg-2 rounded-12 position-absolute top-0 end-0 translate-middle-y px-3 py-2 me-4 text-sm-bold">
        <a href="{{ $category->url }}">{{ $category->name }}</a>
    </label>
@endif

