@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    {!! Theme::get('beforeContent') !!}

    {!! Theme::content() !!}

    {!! Theme::get('afterContent') !!}
@endsection
