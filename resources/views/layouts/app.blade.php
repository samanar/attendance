<!DOCTYPE html>
<html lang = "{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset = "utf-8">
    <meta name    = "viewport" content   = "width=device-width, initial-scale=1">
    <meta name    = "csrf-token" content = "{{ csrf_token() }}">
    <link rel     = "shortcut icon" type = "image/png" href = "{{ asset('favicon.png') }}"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href = "{{ asset('css/uikit.min.css') }}" rel = "stylesheet">
    <link href = "{{ asset('css/style.css') }}" rel     = "stylesheet">
    @stack('styles')
</head>

<body>
  <nav class = "uk-navbar-container uk-margin" uk-navbar>
  <div class = "uk-navbar-center">
  <div class = "uk-navbar-center-left"><div>
  <ul  class = "uk-navbar-nav">
                </ul>
            </div></div>
            <a class = "uk-navbar-item uk-logo" href = "/home">
               Attendance
            </a>
            <div class = "uk-navbar-center-right"><div>
            <ul class = "uk-navbar-nav">
                </ul>
            </div></div>

        </div>
    </nav>
    <div class = "uk-container uk-margin uk-margin-top">
        @yield('content')
    </div>

</body>



<script src = "{{ asset('js/uikit.min.js') }}"></script>
<script src = "{{ asset('js/uikit-icons.min.js') }}"></script>
@stack('scripts')

</html>
