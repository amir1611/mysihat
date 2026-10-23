<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MySihat</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">


    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body id="page-top" class="clinical-body">
    <div id="wrapper" class="clinical-shell">

        @include('layouts.doctor.doctor-sidebar')

        <div id="content-wrapper" class="clinical-content">
            <div id="content">

                @include('layouts.doctor.doctor-topbar')


                <main class="clinical-main">
                    @yield('content')
                </main>

            </div>


        </div>
    </div>
    @stack('scripts')
</body>

</html>
