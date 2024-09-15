@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Hero Section -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center text-md-start"> <!-- Center content on mobile -->
                <h1 class="mb-4"><strong>Connected Care</strong> - Any<span id="changingText">time</span></h1>
                <img src="{{ asset('build/assets/mysihat-svg.svg') }}" alt="MySihat Logo" class="img-fluid mb-4"
                    style="max-width: 100%;" draggable="false">

                <p class="mb-4">Securely care from thousands of high-quality providers accepting patients today.</p>
                <button class="btn btn-primary btn-lg glow-button mb-4 custom-button">
                    <img src="{{ asset('build/assets/mysihatchatbot.png') }}" alt="Chatbot"
                        class="chatbot-icon me-2 pulse-animation">
                    Chat with MySihatBot
                </button>
            </div>
            <div class="col-md-6 d-none d-md-block">
                <dotlottie-player src="https://lottie.host/56123689-d2b0-47ff-bd88-06557ddbaa78/QdasAAWTiE.json"
                    background="transparent" speed="1" style="width: 100%; max-width: 500px; height: auto;"
                    direction="1" playMode="normal" loop autoplay></dotlottie-player>
            </div>
        </div>

        <!-- Featured Services -->
        <div class="bg-white rounded-3 p-4 mb-5 shadow-sm">
            <h2 class="text-center mb-4"><b>Featured Services</b></h2>

            <div class="row g-4 justify-content-center"> <!-- Added justify-content-center -->
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
                        <!-- Adjusted classes and added d-flex -->
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
        <!-- Patient Caring Section -->
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
            <div class="col-md-6 text-center text-md-start"> <!-- Added classes for text alignment -->
            
                <p class="about-mysihat">
                    <b>MySihat</b> developed by InnovateX, is a robust telehealth platform designed
                    to make healthcare accessible anytime, anywhere. Our mission is to bridge the gap between
                    patients and healthcare providers.
                </p>
                <ul class="list-unstyled large-icon-list">
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <!-- Added classes for alignment -->
                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Stay Updated About Your Health</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <!-- Added classes for alignment -->
                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Track Your Health Progress</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <!-- Added classes for alignment -->
                        <i class="bi bi-shield-fill-check text-primary me-3"></i>
                        <span>Manage Your Appointments</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Latest News Section -->
        <h2 class="text-center mb-4">Health Articles</h2>
        <div class="news-swiper swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="news-card">
                        <img src="{{ asset('build/assets/mentalhealth.svg') }}" class="news-card-img" alt="Mental Health">
                        <div class="news-card-body">
                            <h5 class="news-card-title">How to take care of mental health</h5>
                            <p class="news-card-text">Prioritize self-care, manage stress, connect with others, and seek
                                support to maintain strong mental health.</p>
                            <p class="news-card-date"><small>1 September 2024</small></p>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="news-card">
                        <img src="{{ asset('build/assets/disease.svg') }}" class="news-card-img" alt="Communicable and
                                Non-Communicable Diseases">
                        <div class="news-card-body">
                            <h5 class="news-card-title">Communicable and
                                Non-Communicable Diseases</h5>
                            <p class="news-card-text">
                                Communicable diseases spread through infections, while non-communicable diseases stem from lifestyle or genetics, both needing prevention and management.</p>
                            <p class="news-card-date"><small>2 September 2024</small></p>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="news-card">
                        <img src="{{ asset('build/assets/healthyhabits.svg') }}" class="news-card-img" alt="et's learn about healthy habits">
                        <div class="news-card-body">
                            <h5 class="news-card-title">Let's learn about healthy habits</h5>
                            <p class="news-card-text">Healthy habits include eating a balanced diet, exercising regularly, staying hydrated, getting enough sleep, and managing stress effectively.</p>
                            <p class="news-card-date"><small>3 September 2024</small></p>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="news-card">
                        <img src="{{ asset('build/assets/substance.svg') }}" class="news-card-img" alt="Substance Abuse Awareness Program">
                        <div class="news-card-body">
                            <h5 class="news-card-title">Substance Awareness Program</h5>
                            <p class="news-card-text">A Substance Abuse Awareness Program educates on the risks of drug misuse and promotes prevention and recovery resources</p>
                            <p class="news-card-date"><small>4 September 2024</small></p>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- FAQ Section -->
        <h2 class="text-center mb-4"><b>Frequently Asked Questions</b></h2>
        <div class="faq-container mb-5">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is MySihat?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            MySihat is a telehealth platform designed to provide accessible healthcare services anytime, anywhere.
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How do I book an appointment?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can book an appointment by logging into your account and selecting the "Book Appointment" option from the dashboard.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Is my medical information secure?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we use industry-standard encryption and security measures to protect your medical information and ensure your privacy.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center mt-4">
            <div class="container">
                <p class="mb-0">© 2024 InnovateX. All rights reserved for MySihat.</p>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endsection

@section('styles')
<style>
    /* Footer styles */
    footer {
        background-color: #f8f9fa;
        color: #333;
        font-size: 0.9rem;
    }

    /* FAQ styles */
    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .accordion-item {
        border: none;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .accordion-button {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
        border: none;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgb(41, 50, 137);
        color: #fff;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-body {
        background-color: #fff;
        padding: 1rem;
    }
</style>
@endsection
