<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
@include('partials.favicon')

<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Brightfox') }} | @yield('page_title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="wrapper" class="{{ (Request::is('meetups') || Request::is('meetups/filter'))? 'forced enlarged' : '' }}">

    @include('partials.header')
    @include('partials.students_sidebar')

    <div class="content-page">
        <div id="content-page-content" class="content">
            <div class="container">
                @yield('breadcrumbs')
                @yield('content')
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script>
    var resizefunc = [];
</script>
</body>
</html>
