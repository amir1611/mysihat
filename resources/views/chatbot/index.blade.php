@extends('layouts.patient.patient-layout')

@section('content')
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>MySihat Chatbot</title>
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    </head>

    <body>
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                @include('chatbot.partials.chatbubble', [
                    'message' => $greeting,
                    'className' => 'claude-message',
                    'sender' => 'MySihat Bot',
                    'avatarUrl' => '/build/assets/mysihatbot.png',
                ])
            </div>
            <details id="summary-container">
                <summary class="summary-title">Summary</summary>
                <div class="summary-content" id="summaryContent"></div>
            </details>
            <div class="input-area">
                <input type="text" id="messageInput" class="form-control" placeholder="Message MySihat Bot...">
                <button class="btn" type="button" id="sendButton">
                    <i class="fas fa-arrow-up"></i>
                </button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/chat.js') }}"></script>
        <script src="{{ asset('js/tts.js')}}"></script>

    
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Book Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="appointmentForm">
                            <input type="hidden" id="selectedDoctorId" name="doctor_id">
                            <div class="mb-3">
                                <label for="appointmentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate" name="appointment_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="appointmentTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="appointmentTime" name="appointment_time" required>
                            </div>
                            <div class="mb-3">
                                <label for="medicalRecords" class="form-label">Medical Records (Optional)</label>
                                <input type="file" class="form-control" id="medicalRecords" name="medical_records">
                            </div>
                            <div class="mb-3">
                                <label for="currentMedications" class="form-label">Current Medications (Optional)</label>
                                <textarea class="form-control" id="currentMedications" name="current_medications"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitAppointment()">Book Appointment</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
