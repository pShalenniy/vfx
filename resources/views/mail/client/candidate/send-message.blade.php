@extends('layouts.mail')

@section('content')
    <p>{{ $text }}</p>
    <a href="{{ $url }}" target="_blank">
        @lang('client/notification.send_message.action')
    </a>
@endsection
