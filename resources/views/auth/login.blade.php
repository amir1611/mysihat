@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex align-items-center" style="margin-top:80px; background-color: #f7f8fc;">
        <div class="row w-100">
            <!-- Left Lottie Section -->
            <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
                <!-- Lottie Animation -->
                <dotlottie-player src="https://lottie.host/56123689-d2b0-47ff-bd88-06557ddbaa78/QdasAAWTiE.json"
                    background="transparent" speed="1" style="width: 600px; height: 600px" direction="1" playMode="normal"
                    loop autoplay></dotlottie-player>
            </div>
            <!-- Right Form Section -->
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card p-4" style="width: 80%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="text-center mb-4">
                        <img class="mb2" src="{{ asset('build/assets/mysihat-svg.svg') }}" draggable="false"
                            alt="">

                        <h2 class="mb-1">{{ __('Login') }}</h2>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <div class="input-group">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="info@mysihat.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password">

                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex justify-content-end">
                            @if (Route::has('password.request'))
                                <a class="btn "
                                    href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                            @endif
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: rgb(41, 50, 137);">{{ __('Login') }}</button>
                        </div>
                        <div class="text-center">
                            <span>OR</span>
                        </div>

                        <div class="d-grid mt-3">
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">{{ __('Register') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>

    <!-- Include the Lottie Player Script -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endsection
