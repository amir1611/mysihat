@extends('layouts.patient.patient-layout')

@section('content')
<section class="appointment-list">
    <div class="appointment-list-header"><div><span class="eyebrow">CARE SCHEDULE</span><h1>Your appointments</h1><p>See your confirmed requests and plan your next consultation.</p></div><a class="appointment-action" href="{{ route('appointmentCreate') }}"><i class="fas fa-plus mr-1"></i> Book appointment</a></div>
    @if (session('success'))<div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>@endif
    <div class="appointment-list-card">
        @forelse ($appointments as $appointment)
            @php($date = \Illuminate\Support\Carbon::parse($appointment->appointment_time))
            <article class="appointment-list-row"><div class="appointment-date-card"><strong>{{ $date->format('d') }}</strong><span>{{ $date->format('M') }}</span></div><div><h2>Dr. {{ $appointment->doctor?->name ?? 'Care team' }}</h2><p>{{ $appointment->doctor?->expertise ?? 'General consultation' }}</p></div><div class="appointment-kind"><i class="far fa-clock mr-1"></i>{{ $date->format('D, j M · g:i A') }}<br><span>{{ $appointment->reason ?: 'General consultation' }}</span></div><span class="appointment-status">{{ $appointment->status ?? 'Requested' }}</span></article>
        @empty
            <div class="appointment-empty"><i class="far fa-calendar-plus"></i><h2>No appointments yet</h2><p>Start with a conversation or book directly with a clinician.</p><a class="appointment-action d-inline-block" href="{{ route('patient.chatbot') }}">Talk to care assistant</a></div>
        @endforelse
    </div>
</section>
@endsection
