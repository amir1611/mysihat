<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="A2DyFgeLm0yU4SEFBC8Kq31oEL96DJemm5lDGbOf">
    <title>MySihat</title>
    <link rel="icon" type="image/svg+xml" href="http://mysihat.test/build/assets/mysihat-icon.svg">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preload" as="style" href="http://mysihat.test/build/assets/app-DK9YYBtZ.css" />
    <link rel="stylesheet" href="http://mysihat.test/build/assets/app-DK9YYBtZ.css" data-navigate-track="reload" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="http://mysihat.test/css/app.css">
</head>

<body>
    <div id="app">
        <nav class="shadow-sm navbar navbar-expand-lg navbar-dark" style="background-color: #293289;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="http://mysihat.test">
                    <img src="http://mysihat.test/build/assets/mysihat-icon.svg" alt="MySihat Icon" height="30"
                        class="me-2">
                    MySihat
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="http://mysihat.test/patient/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="http://mysihat.test/management/login">Admin Login</a>
                        </li>


                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-md-6 text-center text-md-start">
                        <h1 class="mb-4"><strong>Connected Care</strong> - Any<span id="changingText">time</span></h1>
                        <img src="http://mysihat.test/build/assets/mysihat-svg.svg" alt="MySihat Logo"
                            class="img-fluid mb-4" style="max-width: 100%;" draggable="false">

                        <p class="mb-4">Securely care from thousands of high-quality providers accepting patients
                            today.</p>
                        <a href="http://mysihat.test/patient/chat-bot"><button
                                class="btn btn-primary btn-lg glow-button mb-4 custom-button">
                                <img src="http://mysihat.test/build/assets/mysihatchatbot.png" alt="Chatbot"
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

                        <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">

                            <div class="card-flip">
                                <div class="card-flip-inner">
                                    <div class="card-flip-front">
                                        <i class="bi bi-clipboard2-pulse text-primary"></i>
                                        <h5>Symptom Checker</h5>
                                    </div>
                                    <div class="card-flip-back">
                                        <p>Check symptoms and get instant health advice.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">

                            <div class="card-flip">
                                <div class="card-flip-inner">
                                    <div class="card-flip-front">
                                        <i class="bi bi-chat-dots text-primary"></i>
                                        <h5>MySihatBot</h5>
                                    </div>
                                    <div class="card-flip-back">
                                        <p>Get quick answers to your health questions 24/7 with MySihatBot.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">

                            <div class="card-flip">
                                <div class="card-flip-inner">
                                    <div class="card-flip-front">
                                        <i class="bi bi-journal-medical text-primary"></i>
                                        <h5>Health Library</h5>
                                    </div>
                                    <div class="card-flip-back">
                                        <p>Access a wealth of health information and resources.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">

                            <div class="card-flip">
                                <div class="card-flip-inner">
                                    <div class="card-flip-front">
                                        <i class="bi bi-camera-video text-primary"></i>
                                        <h5>Teleconsultation</h5>
                                    </div>
                                    <div class="card-flip-back">
                                        <p>Connect with healthcare professionals remotely.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <br>

                <h2 class="text-center mb-4"><b>About Us</b></h2>
                <div class="row align-items-center mb-5">

                    <div
                        class="col-md-6 d-flex justify-content-center justify-content-md-end align-items-center py-4 py-md-0">
                        <div class="lottie-wrapper" style="width: 300px; height: 300px;">
                            <dotlottie-player
                                src="https://lottie.host/6456a808-b88a-49a1-8495-275f2ebd4df6/aljFJY1JZv.json"
                                background="transparent" speed="1" style="width: 100%; height: 100%;"
                                direction="1" playMode="normal" loop autoplay>
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



                <h2 class="text-center mb-4">Health Articles</h2>
                <div class="news-swiper swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="news-card">
                                <img src="https://www.sciencealert.com/images/2024/10/GoutAnkle.jpg"
                                    class="news-card-img"
                                    alt="Huge Study Shows Where Gout Comes From, And It&#039;s Not What We Thought - ScienceAlert">
                                <div class="news-card-body">
                                    <h5 class="news-card-title">Huge Study Shows Where Gout Comes From, And It&#039;s
                                        N...</h5>
                                    <p class="news-card-text">Gout is often associated with drinking too much or not
                                        eating healthily enough, but new research sug...</p>
                                    <p class="news-card-date"><small>2 November 2024</small></p>
                                    <a href="https://www.sciencealert.com/huge-study-shows-where-gout-comes-from-and-its-not-what-we-thought"
                                        target="_blank" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <img src="https://scitechdaily.com/images/Brain-Disorder-Glitch-Art-Concept.jpg"
                                    class="news-card-img"
                                    alt="Brain Shrinkage Alert: Cannabis Use in Youth Linked to Structural Brain Changes - SciTechDaily">
                                <div class="news-card-body">
                                    <h5 class="news-card-title">Brain Shrinkage Alert: Cannabis Use in Youth Linke...
                                    </h5>
                                    <p class="news-card-text">Researchers have discovered that adolescent cannabis use
                                        might lead to the cerebral cortex&#039;s thinnin...</p>
                                    <p class="news-card-date"><small>2 November 2024</small></p>
                                    <a href="https://scitechdaily.com/brain-shrinkage-alert-cannabis-use-in-youth-linked-to-structural-brain-changes/"
                                        target="_blank" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <img src="https://ichef.bbci.co.uk/news/1024/branded_news/5678/live/8b692360-98e9-11ef-8538-e1655f5a8342.jpg"
                                    class="news-card-img"
                                    alt="Gaza polio vaccination drive to restart in north of territory - BBC.com">
                                <div class="news-card-body">
                                    <h5 class="news-card-title">Gaza polio vaccination drive to restart in north o...
                                    </h5>
                                    <p class="news-card-text">It comes as 15 UN and humanitarian organisations
                                        described the situation in north Gaza as &quot;apocalypt...</p>
                                    <p class="news-card-date"><small>2 November 2024</small></p>
                                    <a href="https://www.bbc.com/news/articles/cm2mnlg43k4o" target="_blank"
                                        class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <img src="https://fortune.com/img-assets/wp-content/uploads/2024/10/GettyImages-1391266159-e1730217841441.jpg?resize=1200,600"
                                    class="news-card-img"
                                    alt="Stop obsessing over protein. You’re likely getting more than enough, but missing out on this vital nutrient, experts say - Fortune">
                                <div class="news-card-body">
                                    <h5 class="news-card-title">Stop obsessing over protein. You’re likely getting...
                                    </h5>
                                    <p class="news-card-text">If you&#039;re eating enough during the day, you&#039;re
                                        likely meeting or exceeding your protein needs, exper...</p>
                                    <p class="news-card-date"><small>2 November 2024</small></p>
                                    <a href="https://fortune.com/well/article/too-much-protein-not-enough-fiber/"
                                        target="_blank" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
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
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    MySihat is a telehealth platform designed to provide accessible healthcare services
                                    anytime,
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
                                    You can book an appointment by logging into your account and selecting the "Book
                                    Appointment"
                                    option from the dashboard.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Is my medical information secure?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, we use industry-standard encryption and security measures to protect your
                                    medical
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
