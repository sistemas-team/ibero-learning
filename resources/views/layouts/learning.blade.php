<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    {{-- Stylessheets --}}
    <link rel="stylesheet" href="{{ asset('webuni-template/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('webuni-template/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('webuni-template/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('webuni-template/css/style.css') }}">

    @stack('css')
</head>
{{-- Page preloader --}}
<div id="preloader">
    <div class="loader">

    </div>
</div>
@include('partials.learning.navigation')

@yield('hero')
@component('components.alert-component')@endcomponent

@yield('content')

@include('partials.learning.footer')

<body>
    <script src="{{ asset('webuni-template/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('webuni-template/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('webuni-template/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('webuni-template/js/main.js') }}"></script>
    <script src="{{ asset('webuni-template/js/map.js') }}"></script>
    <script src="{{ asset('webuni-template/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('webuni-template/js/owl.carousel.min.js') }}"></script>

    @stack('js')

</body>

</html>
