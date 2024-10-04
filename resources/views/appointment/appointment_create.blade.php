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

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Book a Doctor's Appointment</h3>
        </div>
        <div class="card-body">
            <form id="appointmentForm">

                <!-- Step 2: Medical Information -->
                <div class="step">
                    <h4>Step 1: Medical Information</h4>
                    <div class="mb-4">
                        <label for="reason" class="form-label">Reason for Visit</label>
                        <textarea class="form-control" id="reason" rows="3" placeholder="Describe your symptoms" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="medicalConditionsFile" class="form-label">Upload Medical Records (Optional)</label>
                        <input type="file" class="form-control" id="medicalConditionsFile" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Accepted file types: PDF, DOC, DOCX, TXT, JPG, PNG</small>
                    </div>

                    <div class="mb-4">
                        <label for="medications" class="form-label">Current Medications (Optional)</label>
                        <textarea class="form-control" id="medications" rows="2" placeholder="Enter medications you are taking"></textarea>
                    </div>

                </div>

                <!-- Step 2: Appointment Details -->

                <div class="step">
                    <h4>Step 2: Appointment Details</h4>
                    <div class="mb-4">
                        <label for="doctor" class="form-label">Select a Doctor</label>
                        <select class="form-select" id="doctor" required>
                            <option value="">Select a doctor</option>
                            <option value="1">Dr. John Doe</option>
                            <option value="2">Dr. Jane Smith</option>
                            <option value="3">Dr. Michael Johnson</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="appointmentTime" class="form-label">Preferred Appointment Time</label>
                        <input type="datetime-local" class="form-control" id="appointmentTime" required>
                    </div>
                </div>



                <!-- Step 3: Emergency Contact -->
                <div class="step">
                    <h4>Step 3: Emergency Contact</h4>
                    <div class="mb-4">
                        <label for="emergencyContact" class="form-label">Emergency Contact Name</label>
                        <input type="text" class="form-control" id="emergencyContact" placeholder="Enter emergency contact's name" required>
                    </div>
                    <div class="mb-4">
                        <label for="emergencyPhone" class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" id="emergencyPhone" placeholder="Enter emergency contact's number" required>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>


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

@endsection