@extends('layouts.patient.patient-layout')
@section('content')
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>
    <style>
        .btn {
            line-height: 1.5;
            padding: 0.375rem 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .card.select-doctor {
            margin-bottom: 15px;
            /* Add margin between cards */
        }
        .selected-doctor {
            font-weight: bold;
            color: #007bff;
            margin-top: 14px;
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Book a Doctor's Appointment</h3>
            </div>
            <div class="card-body">
                <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Step 1: Medical Information -->
                    <div class="step">
                        <h4>Step 1: Medical Information</h4>
                        <div class="mb-4">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Describe your symptoms"
                                required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="medicalConditionsFile" class="form-label">Upload Medical Records (Optional)</label>
                            <input type="file" class="form-control" id="medicalConditionsFile"
                                name="medical_conditions_record" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Accepted file types: PDF, DOC, DOCX, TXT, JPG, PNG</small>
                        </div>
                        <div class="mb-4">
                            <label for="medications" class="form-label">Current Medications (Optional)</label>
                            <textarea class="form-control" id="medications" name="current_medications" rows="2"
                                placeholder="Enter medications you are taking"></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Appointment Details -->
                    <div class="step">
                        <h4>Step 2: Appointment Details</h4>
                        <div class="mb-4 mt-4">
                            <input type="hidden" id="selectedDoctorInput" name="selected_doctor" required>
                           
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#doctorModal">Select Doctor</button>
                                <div id="selectedDoctorName" class="form-text selected-doctor">No doctor selected</div>
                        </div>
                        <div class="mb-4">
                            <label for="appointmentTime" class="form-label">Preferred Appointment Time</label>
                            <input type="date" class="form-control" id="appointmentTime" name="appointment_time"
                                required>
                        </div>

                    </div>

                    <!-- Step 3: Emergency Contact -->
                    <div class="step">
                        <h4>Step 3: Emergency Contact</h4>
                        <div class="mb-4">
                            <label for="emergencyContact" class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergencyContact" name="emergency_contact_name"
                                placeholder="Enter emergency contact's name" required>
                        </div>
                        <div class="mb-4">
                            <label for="emergencyPhone" class="form-label">Emergency Contact Number</label>
                            <input type="tel" class="form-control" id="emergencyPhone" name="emergency_contact_number"
                                placeholder="Enter emergency contact's number" required>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="prevBtn"
                            onclick="nextPrev(-1)">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Doctor Selection Modal -->
    <div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="doctorModalLabel">Select Doctor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="doctorList">
                        @foreach ($doctors as $doctor)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm select-doctor" data-doctor-id="{{ $doctor->id }}"
                                    data-doctor-name="{{ $doctor->name }}">
                                    <div class="card-body d-flex align-items-center">
                                        <img src="https://www.gravatar.com/avatar/?d=mp" alt="{{ $doctor->name }}"
                                            class="rounded-circle me-3"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h5 class="card-title mb-1">{{ $doctor->name }}</h5>
                                            <p class="card-text text-muted mb-2">{{ $doctor->department }}</p>
                                            <span class="badge bg-primary">Available</span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 text-end">
                                        <button class="btn btn-outline-primary btn-sm">Select</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var currentStep = 0; // Current step is set to be the first step (0)
        showStep(currentStep); // Display the current step

        function showStep(n) {
            // Get all the steps
            var steps = document.getElementsByClassName("step");

            // Hide all the steps
            for (var i = 0; i < steps.length; i++) {
                steps[i].classList.remove("active");
            }

            // Show the current step
            steps[n].classList.add("active");

            // Update button text
            document.getElementById("prevBtn").style.display = n == 0 ? "none" : "inline";
            document.getElementById("nextBtn").textContent = n == (steps.length - 1) ? "Submit" : "Next";
        }

        function nextPrev(n) {
            var steps = document.getElementsByClassName("step");

            // Exit the function if the form is invalid
            if (n == 1 && !validateForm()) return false;

            // Increase or decrease the current step by 1
            currentStep += n;

            // If the current step is the last, submit the form
            if (currentStep >= steps.length) {
                document.getElementById("appointmentForm").submit();
                return false;
            }

            // Otherwise, display the correct step
            showStep(currentStep);
        }

        function validateForm() {
            // Simple validation: Check if required fields are filled
            var valid = true;
            var step = document.getElementsByClassName("step")[currentStep];
            var inputs = step.getElementsByTagName("input");

            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].hasAttribute("required") && inputs[i].value == "") {
                    inputs[i].classList.add("is-invalid");
                    valid = false;
                } else {
                    inputs[i].classList.remove("is-invalid");
                }
            }
            return valid;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorList = document.getElementById('doctorList');
            const selectedDoctorInput = document.getElementById('selectedDoctorInput');
            const selectedDoctorName = document.getElementById('selectedDoctorName');
            const modalElement = document.getElementById('doctorModal');
            const modal = new bootstrap.Modal(modalElement);

            // Handle doctor selection
            doctorList.addEventListener('click', function(e) {
                const card = e.target.closest('.select-doctor');
                if (card) {
                    const doctorId = card.dataset.doctorId;
                    const doctorName = card.dataset.doctorName;
                    selectedDoctorInput.value = doctorId;
                    selectedDoctorName.textContent = `Selected: ${doctorName}`;

                    // Close the modal
                    //modal.hide();

                    // If the above doesn't work, try this alternative:
                     bootstrap.Modal.getInstance(modalElement).hide();
                }
            });

            // Ensure the modal is properly hidden when the hide event is triggered
            modalElement.addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open');
                const modalBackdrop = document.querySelector('.modal-backdrop');
                if (modalBackdrop) {
                    modalBackdrop.remove();
                }
            });
        });
    </script>
@endsection
