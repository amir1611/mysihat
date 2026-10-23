<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"><title>MySihat</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet"><link href="{{ asset('css/chat.css') }}" rel="stylesheet">
</head>
<body class="clinical-body patient-body"><div class="clinical-shell patient-shell">
    @include('layouts.patient.patient-sidebar')
    <div class="clinical-content">@include('layouts.patient.patient-topbar')<main class="clinical-main"><div class="patient-page">@yield('content')</div></main></div>
</div><script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script><script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>@stack('scripts')</body>
</html>
