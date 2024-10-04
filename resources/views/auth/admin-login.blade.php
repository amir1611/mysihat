@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex align-items-center" style="margin-top:80px; background-color: #f7f8fc;">
        <div class="row w-100">
           
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <div class="card p-4" style="width: 40%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="text-center mb-4">
                        <img class="mb2" src="{{ asset('build/assets/mysihat-svg.svg') }}" draggable="false"
                            alt="">

                        <h2 class="mb-1">
                            <i class="fas fa-user-shield mr-2"></i> {{ __('Admin Login') }}
                        </h2>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                 
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4"></div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password">
                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                       
                        <div class="mb-4"></div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: rgb(41, 50, 137);">
                                <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function (e) {
               
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
              
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        });
    </script>

    <!-- Include the Lottie Player Script -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
@endsection
