<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>{{ config('constants.head.title') }}</title>
    
    <meta name="title" content="{{ config('constants.head.meta_title') }}">
    <meta name="description" content="{{ config('constants.head.meta_description') }}">
    <meta name="keywords" content="{{ config('constants.head.meta_keywords') }}">

    <link rel="canonical" href="{{ config('constants.head.link_canonical') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/global.scss')
    @stack('stylesheets')

</head>