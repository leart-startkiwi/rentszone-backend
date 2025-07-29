@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    @if(Theme::get('breadcrumbs', true))
        {!! Theme::partial('breadcrumbs') !!}
    @endif

    {!! Theme::get('beforeContent') !!}

    <div class="container page-default-content">
        {!! Theme::content() !!}
    </div>

    {!! Theme::get('afterContent') !!}
@endsection
