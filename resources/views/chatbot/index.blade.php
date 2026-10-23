@extends('layouts.patient.patient-layout')

@section('content')
<section class="chat-page">
    <div class="chat-shell">
        <header class="chat-header">
            <div class="chat-title"><img src="{{ asset('build/assets/mysihatbot.png') }}" alt="MySihat Bot"><div><h1>MySihat Care Assistant</h1><p><i class="fas fa-circle mr-1" style="font-size:.5rem"></i>Here to listen, guide, and help you book care</p></div></div>
            <button class="doctor-picker" type="button" id="findDoctorButton"><i class="fas fa-user-md mr-1"></i> Find a doctor</button>
        </header>
        <div class="chat-messages" id="chatMessages">
            @include('chatbot.partials.chatbubble', ['message' => e($greeting), 'className' => 'claude-message', 'sender' => 'MySihat Care Assistant', 'avatarUrl' => asset('build/assets/mysihatbot.png')])
        </div>
        <div class="chat-helper"><span>You can keep talking for as long as you need. When you are ready, we will match you with a doctor.</span><button type="button" id="findDoctorHelper">Choose a doctor</button></div>
        <div class="input-area"><input type="text" id="messageInput" class="form-control" autocomplete="off" placeholder="Tell me how you are feeling…"><button class="btn" type="button" id="micButton" aria-label="Speak your message"><i class="fas fa-microphone"></i></button><button class="btn" type="button" id="sendButton" aria-label="Send message"><i class="fas fa-arrow-up"></i></button></div>
    </div>
</section>

<div class="modal fade booking-modal" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="bookingModalLabel">Book your appointment</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><form id="appointmentForm"><div class="modal-body"><input type="hidden" id="selectedDoctorId" name="doctor_id"><p class="text-muted small" id="selectedDoctorName"></p><div class="mb-3"><label for="appointmentDate" class="form-label">Preferred date</label><input type="date" class="form-control" id="appointmentDate" name="appointment_date" required></div><div class="mb-3"><label for="appointmentTime" class="form-label">Preferred time</label><input type="time" class="form-control" id="appointmentTime" name="appointment_time" required></div><div class="mb-3"><label for="appointmentReason" class="form-label">What would you like help with? <span class="text-muted">(optional)</span></label><textarea class="form-control" id="appointmentReason" name="reason" rows="3"></textarea></div><div class="mb-3"><label for="currentMedications" class="form-label">Current medications <span class="text-muted">(optional)</span></label><textarea class="form-control" id="currentMedications" name="current_medications" rows="2"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary" id="bookAppointmentButton">Confirm booking</button></div></form></div></div></div>
@endsection

@push('scripts')
<script src="{{ asset('js/chat.js') }}"></script><script src="{{ asset('js/tts.js') }}"></script><script src="{{ asset('js/speech.js') }}"></script>
@endpush
