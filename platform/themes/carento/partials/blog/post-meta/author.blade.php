@if ($author = $post->author)
    <div class="card-author">
        {{ RvMedia::image($author->avatar_url, $author->name, 'thumb', attributes: ['class' => 'rounded-circle border border-primary', 'width' => 32, 'height' => 32]) }}
        <p class="text-sm-bold neutral-1000">{{ $author->name }}</p>
    </div>
@endif



