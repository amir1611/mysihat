@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center text-md-start">
                <h1 class="mb-4"><strong>Connected Care</strong> - Any<span id="changingText">time</span></h1>
                    <img src="{{ asset('build/assets/mysihat-svg.svg') }}" alt="MySihat Logo" class="img-fluid mb-4"
                        style="max-width: 100%;" draggable="false">

                    <p class="mb-4">Securely care from thousands of high-quality providers accepting patients today.</p>
                    <a href="{{url('/patient/chat-bot')}}"><button class="btn btn-primary btn-lg glow-button mb-4 custom-button">
                        <img src="{{ asset('build/assets/mysihatchatbot.png') }}" alt="Chatbot"
                            class="chatbot-icon me-2 pulse-animation">
                        Chat with MySihatBot
                    </button></a>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <dotlottie-player src="https://lottie.host/56123689-d2b0-47ff-bd88-06557ddbaa78/QdasAAWTiE.json"
                    background="transparent" speed="1" style="width: 100%; max-width: 500px; height: auto;"
                    direction="1" playMode="normal" loop autoplay></dotlottie-player>
            </div>
        </div>


        <div class="bg-white rounded-3 p-4 mb-5 shadow-sm">
            <h2 class="text-center mb-4"><b>Featured Services</b></h2>

            <div class="row g-4 justify-content-center">
                @php
                    $services = [
                        [
                            'icon' => 'bi-clipboard2-pulse',
                            'title' => 'Symptom Checker',
                            'description' => 'Check symptoms and get instant health advice.',
                        ],
                        [
                            'icon' => 'bi-chat-dots',
                            'title' => 'MySihatBot',
                            'description' => 'Get quick answers to your health questions 24/7 with MySihatBot.',
                        ],
                        [
                            'icon' => 'bi-journal-medical',
                            'title' => 'Health Library',
                            'description' => 'Access a wealth of health information and resources.',
                        ],
                        [
                            'icon' => 'bi-camera-video',
                            'title' => 'Teleconsultation',
                            'description' => 'Connect with healthcare professionals remotely.',
                        ],
                    ];
                @endphp

                @foreach ($services as $service)
                    <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">

                        <div class="card-flip">
                            <div class="card-flip-inner">
                                <div class="card-flip-front">
                                    <i class="bi {{ $service['icon'] }} text-primary"></i>
                                    <h5>{{ $service['title'] }}</h5>
                                </div>
                                <div class="card-flip-back">
                                    <p>{{ $service['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <br>
        <br>

        <h2 class="text-center mb-4"><b>About Us</b></h2>
        <div class="row align-items-center mb-5">

            <div class="col-md-6 d-flex justify-content-center justify-content-md-end align-items-center py-4 py-md-0">
                <div class="lottie-wrapper" style="width: 300px; height: 300px;">
                    <dotlottie-player src="https://lottie.host/6456a808-b88a-49a1-8495-275f2ebd4df6/aljFJY1JZv.json"
                        background="transparent" speed="1" style="width: 100%; height: 100%;" direction="1"
                        playMode="normal" loop autoplay>
                    </dotlottie-player>
                </div>
            </div>
            <div class="col-md-6 text-center text-md-start">

                <p class="about-mysihat">
                    <b>MySihat</b> developed by InnovateX, is a robust telehealth platform designed
                    to make healthcare accessible anytime, anywhere. Our mission is to bridge the gap between
                    patients and healthcare providers.
                </p>
                <ul class="list-unstyled large-icon-list">
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">

                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Stay Updated About Your Health</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">

                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Track Your Health Progress</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">

                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Manage Your Appointments</span>
                    </li>
                </ul>
            </div>
        </div>


        @php
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'category' => 'health',
                'apiKey' => env('NEWS_API_KEY'),
            ]);

            $data = $response->json();
            $articles = collect($data['articles'] ?? [])->filter(function ($article) {
                return !empty($article['title']) && !empty($article['description']) && !empty($article['url']);
            })->take(4);
        @endphp

        <h2 class="text-center mb-4">Health Articles</h2>
        <div class="news-swiper swiper">
            <div class="swiper-wrapper">
                @if ($response->successful() && $articles->count() > 0)
                    @foreach ($articles as $article)
                        <div class="swiper-slide">
                            <div class="news-card">
                                <img src="{{ $article['urlToImage'] ?? asset('build/assets/placeholder.svg') }}" class="news-card-img" alt="{{ $article['title'] }}">
                                <div class="news-card-body">
                                    <h5 class="news-card-title">{{ Str::limit($article['title'], 50) }}</h5>
                                    <p class="news-card-text">{{ Str::limit($article['description'], 100) }}</p>
                                    <p class="news-card-date"><small>{{ \Carbon\Carbon::parse($article['publishedAt'])->format('j F Y') }}</small></p>
                                    <a href="{{ $article['url'] }}" target="_blank" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <div class="news-card">
                            <div class="news-card-body">
                                <h5 class="news-card-title">No articles available</h5>
                                <p class="news-card-text">Unable to fetch health articles at the moment. Please try again later.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="swiper-pagination"></div>
        </div>


        <h2 class="text-center mb-4"><b>Frequently Asked Questions</b></h2>
        <div class="faq-container mb-5">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is MySihat?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            MySihat is a telehealth platform designed to provide accessible healthcare services anytime,
                            anywhere.
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I book an appointment?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can book an appointment by logging into your account and selecting the "Book Appointment"
                            option from the dashboard.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Is my medical information secure?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we use industry-standard encryption and security measures to protect your medical
                            information and ensure your privacy.
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <footer class="bg-light text-center mt-4">
            <div class="container">
                <p class="mb-0">© 2024 InnovateX. All rights reserved for MySihat.</p>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endsection