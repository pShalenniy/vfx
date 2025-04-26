<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{!! Config::get('app.name') !!}</title>
    <link rel="stylesheet" href="{!! URL::mix('/css/vendor.css') !!}">
    <link rel="stylesheet" href="{!! URL::mix('/css/common.css') !!}">
    <link rel="stylesheet" href="{{ URL::mix('/css/admin.css') }}">
</head>
<body>
<div id="app">
    <router-view></router-view>
</div>
<script type="text/javascript">
    window.globalSettingsKey = '{{ \App\Helpers\JsHelper::getKey() }}';
    window[window.globalSettingsKey] = {!! \App\Helpers\JsHelper::toJson() !!};
</script>
@foreach (App\Helpers\JsHelper::getChunkedJsFiles('admin') as $chunk)
    <script src="{{ $chunk }}"></script>
@endforeach
<script type="text/javascript" src="{{ URL::mix('/js/admin.js') }}"></script>
<script type="text/javascript" src="{{ URL::mix('/js/vendor.js') }}"></script>
@yield('scripts')
</body>
</html>
