<div class="box-grid-hotels box-grid-news mt-60 mb-50 wow fadeIn">
    @foreach($posts as $post)
        {!! Theme::partial('blog.posts.item-list', compact('post')) !!}
    @endforeach
</div>
