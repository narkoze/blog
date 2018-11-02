@extends('message.layout')
@section('content')

  @lang('message.passwordreset.content')

  @component('mail::button', [
      'url' => url(config('app.url') . '/' . Lang::locale() . "/passwordreset/$token/$email"),
  ])
    @lang('message.passwordreset.button')
  @endcomponent

  @lang('message.passwordreset.info')

@endsection
