@component('mail::message')

  # @lang('message.greeting'),

  @yield('content')

  @lang('message.layout.salutation'),
  <br>
  {{ config('app.name') }}

@endcomponent
