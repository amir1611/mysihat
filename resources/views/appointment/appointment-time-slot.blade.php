@extends('layouts.doctor.doctor-layout')

@section('content')


<div class="container mt-5">
    <!-- Title and Add Slot Button -->
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 my-4 text-gray-800">Available Time Slots</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlotModal">Add Slot</button>
    </div>

    <!-- Time Slot Table -->
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time Slots</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="slotTableBody">
            <!-- Example row -->
            <tr>
                <td>
                    <div class="d-flex align-items-center">

                        <div class="">
                            <p class="fw-bold mb-1">2024-10-05</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="fw-normal mb-1">09:00 AM - 10:00 AM</p>
                    <p class="text-muted mb-0">11:00 AM - 12:00 PM</p>
                </td>
                <td>
                    <span class="badge badge-success rounded-pill d-inline">Available</span>
                </td>
                <td>
                    <button type="button" class="btn btn-link btn-sm btn-rounded edit-slot-btn">Edit</button>
                    <button type="button" class="btn btn-link btn-sm btn-rounded text-danger delete-slot-btn">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Add/Edit Slot Modal -->
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-labelledby="addSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSlotModalLabel">Add Time Slots</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="slotForm">
                    <!-- Date Picker -->
                    <div class="mb-3">
                        <label for="slotDate" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="slotDate" required>
                    </div>

                    <!-- Time Slot Input (multiple) -->
                    <div id="timeSlotsContainer" class="mb-3">
                        <label for="timeSlot1" class="form-label">Add Time Slot</label>
                        <input type="time" class="form-control mb-2" id="timeSlot1" required>
                    </div>

                    <div class="mt-5">
                        <!-- Add Another Time Slot Button -->
                    <button type="button" class="btn btn-secondary " id="addTimeSlot">Add Another Slot</button>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save Slots</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript for adding, editing, and deleting slots

    let editingRow = null; // Track the row being edited

    // Add new time slot
    document.getElementById("addTimeSlot").addEventListener("click", function() {
        const timeSlotsContainer = document.getElementById("timeSlotsContainer");
        const slotCount = timeSlotsContainer.getElementsByTagName("input").length + 1;

        // Create new time input field
        const newTimeSlot = document.createElement("input");
        newTimeSlot.type = "time";
        newTimeSlot.className = "form-control mb-2";
        newTimeSlot.id = "timeSlot" + slotCount;
        newTimeSlot.required = true;

        timeSlotsContainer.appendChild(newTimeSlot);
    });

    // Handle form submit for adding or editing
    document.getElementById("slotForm").addEventListener("submit", function(event) {
        event.preventDefault();

        // Get the selected date
        const date = document.getElementById("slotDate").value;

        // Collect all time slots
        const timeSlotInputs = document.querySelectorAll("#timeSlotsContainer input");
        const timeSlots = [];
        timeSlotInputs.forEach(slot => {
            timeSlots.push(slot.value);
        });

        // Format time slots as string (for displaying in table)
        const formattedSlots = timeSlots.join(', ');

        // Check if editing or adding new
        if (editingRow) {
            // Update existing slot
            const slotId = editingRow.dataset.id; // Assuming you have a data-id attribute for the row
            fetch(`/api/time-slots/${slotId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                body: JSON.stringify({ date, timeSlots })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editingRow.cells[0].querySelector('.fw-bold').innerText = date;
                    editingRow.cells[1].innerText = formattedSlots;
                    editingRow = null; // Reset after editing
                } else {
                    alert('Error updating slot');
                }
            });
        } else {
            // Create new table row
            fetch('/api/time-slots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                body: JSON.stringify({ date, timeSlots })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newRow = document.createElement("tr");
                    newRow.dataset.id = data.slot.id; // Assuming the response contains the new slot ID
                    newRow.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <p class="fw-bold mb-1">${date}</p>
                            </div>
                        </td>
                        <td>${formattedSlots}</td>
                        <td><span class="badge badge-success rounded-pill d-inline">Available</span></td>
                        <td>
                            <button class="btn btn-link btn-sm btn-rounded edit-slot-btn">Edit</button>
                            <button class="btn btn-link btn-sm btn-rounded text-danger delete-slot-btn">Delete</button>
                        </td>
                    `;
                    document.getElementById("slotTableBody").appendChild(newRow);
                } else {
                    console.log(data.error);
                    alert('Error adding slot');
                    
                }
            });
        }

        // Close modal and reset form
        const modal = bootstrap.Modal.getInstance(document.getElementById("addSlotModal"));
        modal.hide();
        document.getElementById("slotForm").reset();

        // Remove additional time slot inputs (reset to only one)
        const firstTimeSlot = document.getElementById("timeSlot1");
        document.getElementById("timeSlotsContainer").innerHTML = '';
        document.getElementById("timeSlotsContainer").appendChild(firstTimeSlot);
    });

    // Handle edit and delete actions using event delegation
    document.getElementById("slotTableBody").addEventListener("click", function(event) {
        const target = event.target;

        if (target.classList.contains("delete-slot-btn")) {
            // Delete the row
            const slotId = target.closest("tr").dataset.id; // Get the slot ID
            fetch(`/api/time-slots/${slotId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    target.closest("tr").remove();
                } else {
                    alert('Error deleting slot');
                }
            });
        } else if (target.classList.contains("edit-slot-btn")) {
            // Edit the row
            editingRow = target.closest("tr");

            // Populate the modal with the existing data
            const date = editingRow.cells[0].querySelector('.fw-bold').innerText;
            const timeSlots = editingRow.cells[1].innerText.split(", ");

            document.getElementById("slotDate").value = date;
            document.getElementById("timeSlotsContainer").innerHTML = '';

            timeSlots.forEach((timeSlot, index) => {
                const timeInput = document.createElement("input");
                timeInput.type = "time";
                timeInput.className = "form-control mb-2";
                timeInput.id = "timeSlot" + (index + 1);
                timeInput.value = timeSlot;
                timeInput.required = true;
                document.getElementById("timeSlotsContainer").appendChild(timeInput);
            });

            // Open the modal for editing
            const modal = new bootstrap.Modal(document.getElementById("addSlotModal"));
            modal.show();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@endsection
