<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @if (config('app.google_analytics_id'))
      <script
        async
        src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytics_id') }}"
      >
      </script>
      <script>
        window.dataLayer = window.dataLayer || []
        function gtag() {
          dataLayer.push(arguments)
        }
        gtag('js', new Date())
        gtag('config', @json(config('app.google_analytics_id')))
      </script>
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('welcome.title')</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}">
  </head>
  <body class="has-navbar-fixed-top">
    <blog id="blog"></blog>
    <script src="{{ mix('js/blog.js') }}"></script>
  </body>
</html>
