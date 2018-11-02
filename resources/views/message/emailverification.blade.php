@extends('message.layout')
@section('content')

  @lang('message.emailverification.content')

  @component('mail::button', [
      'url' => "$action",
  ])
    @lang('message.emailverification.button')
  @endcomponent

  @lang('message.emailverification.info')

@endsection
