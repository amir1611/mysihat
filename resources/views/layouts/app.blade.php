<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MySihat</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss'])
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="shadow-sm navbar navbar-expand-lg navbar-dark" style="background-color: #293289;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('build/assets/mysihat-icon.svg') }}" alt="MySihat Icon" height="30"
                        class="me-2">
                    MySihat
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto">
                        {{-- @guest

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('health-articles') }}">
                                <i class="fas fa-book-medical me-1"></i>{{ __('Health Articles') }}
                            </a>
                        </li>
                            @if (Route::has('login')) --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/patient/login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                            </a>
                        </li>
                        {{-- @endif --}}
                        {{-- @if (Route::has('login')) --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ url('/management/login') }}">{{ __('Admin Login') }}</a>
                        </li> --}}
                        {{-- @endif --}}
                        {{-- @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest --}}
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const changingText = document.getElementById('changingText');
        const words = ['time', 'where'];
        let currentIndex = 0;
        setInterval(() => {
            currentIndex = (currentIndex + 1) % words.length;
            setTimeout(() => {
                changingText.textContent = words[currentIndex];
            }, 2000);
        }, 4000);
        new Swiper('.news-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active',
            },
        });
    });
</script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</html>
