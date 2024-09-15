<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MySihat</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Include Bootstrap Icons CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Include Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <style>
        .card-flip {
            perspective: 1000px;
            height: 120px;
            width: 100%;
            max-width: 280px;
            margin: 0 auto 20px;
        }

        .card-flip-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .card-flip:hover .card-flip-inner {
            transform: rotateY(180deg);
        }

        .card-flip-front,
        .card-flip-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-flip-front {
            background-color: white;
        }

        .card-flip-back {
            background-color: #f8f9fa;
            transform: rotateY(180deg);
        }

        .card-flip i {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .card-flip h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }

        .card-flip p {
            font-size: 0.85rem;
            line-height: 1.3;
        }

        @keyframes fadeOutIn {

            0%,
            25% {
                opacity: 1;
                transform: translateY(0);
            }

            45% {
                opacity: 0;
                transform: translateY(-20px);
            }

            50% {
                opacity: 0;
                transform: translateY(20px);
            }

            70%,
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #changingText {
            display: inline-block;
            animation: fadeOutIn 4s ease-in-out infinite;
        }

        @media (max-width: 768px) {
            .card-flip {
                height: 150px;
            }

            .card-flip h5 {
                font-size: 0.9rem;
            }

            .card-flip p {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
            }

            .card-flip {
                height: 180px;
            }
        }

        .custom-button {
            background-color: rgb(41, 50, 137);
            border-color: rgb(41, 50, 137);
            font-size: 1.25rem;
            padding: 12px 24px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .custom-button:hover {
            background-color: rgb(31, 40, 127);
            border-color: rgb(31, 40, 127);
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .chatbot-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Optional: Add hover effect to pause animation */
        .custom-button:hover .pulse-animation {
            animation-play-state: paused;
        }

        .bi-check-circle-fill {
            font-size: 1.2rem;
        }

        .large-icon-list .bi {
            font-size: 1rem;
            /* Adjust this value to make icons larger or smaller */
        }

        .large-icon-list li {
            font-size: 1.1rem;
            /* Optionally increase text size */
        }

        .lottie-wrapper {
            transition: margin-top 0.3s ease;
        }

        @media (max-width: 767px) {
            .lottie-wrapper {
                margin-top: -80px;
            }
        }

        .about-mysihat {
            font-size: 1.2rem;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 1.5rem;
        }

        .about-mysihat b {
            font-size: 1.3rem;
            color: #007bff;
            /* Or any color that matches your theme */
        }

        /* News Swiper styles */
        .news-swiper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .news-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            width: 90%;
            margin: 0 auto;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-card-body {
            padding: 20px;
        }

        .news-card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .news-card-text {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .news-card-date {
            font-size: 0.8rem;
            color: #999;
            margin-bottom: 15px;
        }

        .swiper-pagination {
            position: relative;
            bottom: 0;
            margin-top: 20px;
        }

        .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(41, 50, 137, 0.2);
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: rgb(41, 50, 137);
        }

        /* FAQ styles */
        .faq-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .accordion-item {
            border: none;
            margin-bottom: 10px;
        }

        .accordion-button {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgb(41, 50, 137);
            color: #fff;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, .125);
        }

        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .accordion-body {
            background-color: #fff;
            border-radius: 0 0 10px 10px;
            padding: 15px 20px;
        }

        /* Footer styles */
        footer {
            background-color: #f8f9fa;
            color: #333;
            font-size: 0.9rem;
        }

        /* Add this new style for the READ MORE button */
        .btn-primary {
            background-color: rgb(41, 50, 137);
            border-color: rgb(41, 50, 137);
        }

        /* ... (rest of the existing styles) ... */
    </style>

    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: rgb(41, 50, 137);">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('build/assets/mysihat-icon.svg') }}" alt="MySihat Icon" height="30" class="me-2">
                    MySihat
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.login') }}">{{ __('Admin Login') }}</a>
                                </li>
                            @endif
                        @else
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
                        @endguest
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

        // Initialize Swiper with updated configuration
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

<!-- Include Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</html>
