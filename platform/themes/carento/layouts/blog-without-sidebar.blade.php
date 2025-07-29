@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    @if(Theme::get('breadcrumbs', true))
        {!! Theme::partial('breadcrumbs') !!}
    @endif

    {!! Theme::get('beforeContent') !!}

    {!! dynamic_sidebar('above_blog_list_sidebar') !!}

    <section class="box-section background-body">
        <div class="container">
            <div class="section-box background-body py-96">
                <div class="container">
                    <div class="row">
                        {!! Theme::content() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {!! Theme::get('afterContent') !!}
@endsection
