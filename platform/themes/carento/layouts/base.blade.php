<!doctype html>
<html {!! Theme::htmlAttributes() !!} data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! Theme::partial('css-variable-declare') !!}

    {!! Theme::header() !!}
</head>

<body {!! Theme::bodyAttributes() !!} >

{!! apply_filters(THEME_FRONT_BODY, null) !!}

{!! Theme::partial('header') !!}

<main>
    @yield('content')
</main>

<script>
    'use strict';

    window.siteConfig = {};
</script>

{!! Theme::partial('footer') !!}

{!! Theme::footer() !!}
</body>
</html>
