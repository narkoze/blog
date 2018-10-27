<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('welcome.title')</title>

    <link rel="stylesheet" href="{{ mix('css/blog.css') }}">
  </head>
  <body>
    <div id="blog">
      <blog></blog>
    </div>
    <script src="{{ mix('js/blog.js') }}"></script>
  </body>
</html>
