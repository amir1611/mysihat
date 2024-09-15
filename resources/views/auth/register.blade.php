@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex align-items-center" style="margin-top:80px; background-color: #f7f8fc;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus
                                            placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ic_number"
                                    class="col-md-4 col-form-label text-md-end">{{ __('IC Number') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input id="ic_number" type="text"
                                            class="form-control @error('ic_number') is-invalid @enderror" name="ic_number"
                                            value="{{ old('ic_number') }}" required autocomplete="ic_number"
                                            placeholder="Enter your 12-digit IC number">
                                    </div>
                                    @error('ic_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="gender"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i id="genderIcon"
                                                class="fas fa-venus-mars"></i></span>
                                        <input id="gender" type="text"
                                            class="form-control @error('gender') is-invalid @enderror" name="gender"
                                            required readonly>
                                    </div>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="Enter your email address">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password" placeholder="Enter your password">
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
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm your password">
                                        <span class="input-group-text" id="toggleConfirmPassword"
                                            style="cursor: pointer;">
                                            <i class="bi bi-eye-slash" id="confirmEyeIcon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="date_of_birth"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input id="date_of_birth" type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    </div>
                                    @error('date_of_birth')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone_number"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <span class="input-group-text">+60</span>
                                        <input id="phone_number" type="tel"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            name="phone_number" value="{{ old('phone_number') }}" required
                                            placeholder="Enter your phone number">
                                    </div>
                                    
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="type"
                                    class="col-md-4 col-form-label text-md-end">{{ __('User Type') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select id="type" class="form-select @error('type') is-invalid @enderror"
                                            name="type" required>
                                            <option value="patient" {{ old('type') == 'patient' ? 'selected' : '' }}>
                                                Patient</option>
                                            <option value="doctor" {{ old('type') == 'doctor' ? 'selected' : '' }}>Doctor
                                            </option>
                                        </select>
                                    </div>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3" id="medical_license_container" style="display: none;">
                                <label for="medical_license_number"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Medical License Number') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input id="medical_license_number" type="text"
                                            class="form-control @error('medical_license_number') is-invalid @enderror"
                                            name="medical_license_number" value="{{ old('medical_license_number') }}"
                                            placeholder="Enter your medical license number">
                                    </div>
                                    @error('medical_license_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3" id="medical_license_document_container" style="display: none;">
                                <label for="medical_license_document" class="col-md-4 col-form-label text-md-end">{{ __('Medical License Document') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-pdf"></i></span>
                                        <input id="medical_license_document" type="file" class="form-control @error('medical_license_document') is-invalid @enderror" name="medical_license_document" accept=".pdf">
                                    </div>
                                    @error('medical_license_document')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const medicalLicenseContainer = document.getElementById('medical_license_container');
            const medicalLicenseDocumentContainer = document.getElementById('medical_license_document_container');
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const genderIcon = document.getElementById('genderIcon');
            const icNumberInput = document.getElementById('ic_number');
            const genderInput = document.getElementById('gender');
            const phoneInput = document.getElementById('phone_number');

            function toggleDoctorFields() {
                if (typeSelect.value === 'doctor') {
                    medicalLicenseContainer.style.display = 'flex';
                    medicalLicenseDocumentContainer.style.display = 'flex';
                } else {
                    medicalLicenseContainer.style.display = 'none';
                    medicalLicenseDocumentContainer.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', toggleDoctorFields);

            // Call the function on page load to set the initial state
            toggleDoctorFields();

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });

            // Highlight fields with errors
            document.querySelectorAll('.is-invalid').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    this.nextElementSibling.style.display = 'none';
                });
            });

            // Code for confirm password
            const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
            const confirmPassword = document.querySelector('#password-confirm');
            const confirmEyeIcon = document.querySelector('#confirmEyeIcon');

            toggleConfirmPassword.addEventListener('click', function(e) {
                // toggle the type attribute
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                // toggle the eye slash icon
                confirmEyeIcon.classList.toggle('bi-eye');
                confirmEyeIcon.classList.toggle('bi-eye-slash');
            });

            // Gender icon change
            icNumberInput.addEventListener('input', function() {
                const icNumber = this.value.replace(/\D/g, ''); // Remove non-digit characters
                if (icNumber.length >= 12) {
                    const lastDigit = parseInt(icNumber.slice(-1));
                    if (lastDigit % 2 === 0) {
                        genderInput.value = 'Female';
                        genderIcon.className = 'fas fa-venus';
                    } else {
                        genderInput.value = 'Male';
                        genderIcon.className = 'fas fa-mars';
                    }
                } else {
                    genderInput.value = '';
                    genderIcon.className = 'fas fa-venus-mars';
                }
            });

            phoneInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Ensure the input doesn't exceed 10 digits
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }

                // Update the placeholder to guide the user
                if (this.value.length === 0) {
                    this.placeholder = "Enter your phone number";
                } else {
                    this.placeholder = "";
                }
            });

            // Format the phone number when the form is submitted
            document.querySelector('form').addEventListener('submit', function(e) {
                phoneInput.value = '+60' + phoneInput.value;
            });
        });
    </script>
@endsection
