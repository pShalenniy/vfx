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
    <link rel="stylesheet" href="{{ URL::mix('/css/client.css') }}">
</head>
<body>
<div id="app" class="page position-relative">
    <b-overlay :show="appStore.layout.overlay" rounded="sm" fixed no-wrap z-index="9999"></b-overlay>

    @include('layouts.partials.header')
    <main class="main-content">
        @yield('content')
    </main>
    @include('layouts.partials.footer')
</div>

<script type="text/javascript">
    window.globalSettingsKey = '{{ \App\Helpers\JsHelper::getKey() }}';
    window[window.globalSettingsKey] = {!! \App\Helpers\JsHelper::toJson() !!};
</script>
@foreach (App\Helpers\JsHelper::getChunkedJsFiles('client') as $chunk)
    <script src="{{ $chunk }}"></script>
@endforeach

<script type="text/javascript" src="{{ URL::mix('/js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ URL::mix('/js/client.js') }}"></script>
</body>
</html>
