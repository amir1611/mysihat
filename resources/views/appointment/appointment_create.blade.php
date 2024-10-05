@extends('layouts.patient.patient-layout')
@section('content')

<style>
    .step {
        display: none;
    }

    .step.active {
        display: block;
    }

    /* .btn {
        line-height: 1.5;
        padding: 0.375rem 0.75rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    } */
    .fixed-height-btn {
    height: 40px; /* Set a fixed height */
    width: fit-content; /* Set a fixed width */
    padding: 10px 20px; /* Adjust padding as needed */
    box-sizing: border-box; /* Ensure padding and border are included in the height */
}

    .fixed-height-btn:active {
        height: 40px; /* Maintain the same height on click */
        width: fit-content; /* Maintain the same width on click */
    }

    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<form >
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
        <h4 class="mb-4">Step 2: Appointment Details</h4>

        <!-- Doctor Selection Button -->
        <div class="mb-4">
            <button type="button" class="btn btn-primary btn-sm fixed-height-btn" data-mdb-toggle="modal" data-mdb-target="#doctorModal">
                Select a Doctor
            </button>
            <input type="hidden" id="selectedDoctor" required>
            <div id="selectedDoctorName" class="mt-2 text-muted"></div>
        </div>

        <!-- Appointment Time -->
        <div class="mb-4">
            <label for="appointmentTime" class="form-label">Preferred Appointment Time</label>
            <input type="datetime-local" class="form-control" id="appointmentTime" required>
        </div>
    </div>

    <!-- Doctor Selection Modal -->
    <div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorModalLabel">Select a Doctor</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Department Filter -->
                    <div class="mb-4">
                        <label for="department" class="form-label">Filter by Department</label>
                        <select class="form-select" id="department">
                            <option value="">All Departments</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="neurology">Neurology</option>
                            <option value="pediatrics">Pediatrics</option>
                        </select>
                    </div>
                    <div class="row" id="doctorList">
                        <!-- Doctor cards will be dynamically inserted here -->
                    </div>
                </div>
            </div>
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
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
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
    // Sample doctor data
    const doctors = [
        { id: 1, name: "Dr. John Doe", department: "cardiology", image: "https://mdbootstrap.com/img/new/avatars/2.jpg" },
        { id: 2, name: "Dr. Jane Smith", department: "neurology", image: "https://mdbootstrap.com/img/new/avatars/5.jpg" },
        { id: 3, name: "Dr. Michael Johnson", department: "pediatrics", image: "https://mdbootstrap.com/img/new/avatars/8.jpg" },
        { id: 4, name: "Dr. Emily Brown", department: "cardiology", image: "https://mdbootstrap.com/img/new/avatars/6.jpg" },
        { id: 4, name: "Dr. Emily Brown", department: "cardiology", image: "https://mdbootstrap.com/img/new/avatars/6.jpg" },
        { id: 4, name: "Dr. Emily Brown", department: "cardiology", image: "https://mdbootstrap.com/img/new/avatars/6.jpg" },
    ];

    // Function to create doctor cards
    function createDoctorCard(doctor) {
        return `
            <div class="col-md-6 mb-4" data-department="${doctor.department}">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <img src="${doctor.image}" class="rounded-circle me-3" alt="${doctor.name}" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h5 class="card-title mb-0">${doctor.name}</h5>
                            <p class="card-text text-muted">${doctor.department}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm w-100 select-doctor" data-doctor-id="${doctor.id}" data-doctor-name="${doctor.name}">Select</button>
                    </div>
                </div>
            </div>
        `;
    }

    // Populate doctor list
    function populateDoctorList() {
        const doctorList = document.getElementById('doctorList');
        doctorList.innerHTML = doctors.map(createDoctorCard).join('');
    }

    // Filter doctors by department
    document.getElementById('department').addEventListener('change', function() {
        const selectedDepartment = this.value;
        const doctorCards = document.querySelectorAll('#doctorList > div');

        doctorCards.forEach(card => {
            if (selectedDepartment === '' || card.dataset.department === selectedDepartment) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle doctor selection
    document.getElementById('doctorList').addEventListener('click', function(e) {
        if (e.target.classList.contains('select-doctor')) {
            const doctorId = e.target.dataset.doctorId;
            const doctorName = e.target.dataset.doctorName;
            document.getElementById('selectedDoctor').value = doctorId;
            document.getElementById('selectedDoctorName').textContent = `Selected: ${doctorName}`;
            document.querySelector('#doctorModal .btn-close').click();
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        populateDoctorList();
    });
</script>


@endsection
