@extends('layouts.doctor.doctor-layout')

@section('content')
<div class="health-dashboard clinician-page">
    <section class="dashboard-hero doctor-hero compact-hero"><div><span class="eyebrow">APPOINTMENT QUEUE</span><h1>Plan your care day.</h1><p>Review upcoming consultations and prepare for every patient conversation.</p></div><a class="primary-action" href="{{ route('time-slots.index') }}"><i class="fas fa-clock"></i> Set availability</a></section>
    <section class="panel data-panel"><div class="panel-heading"><div><span class="eyebrow">YOUR SCHEDULE</span><h2>All appointments</h2></div><span class="table-count">{{ $appointments->count() }} scheduled</span></div>
    <div class="table-responsive"><table class="admin-table"><thead><tr><th>Patient</th><th>Appointment</th><th>Reason</th><th>Care details</th></tr></thead><tbody>
    @forelse($appointments as $appointment)<tr><td><div class="table-person"><span class="member-avatar">{{ strtoupper(substr(optional($appointment->patient)->name ?? 'P', 0, 1)) }}</span><div><strong>{{ optional($appointment->patient)->name ?? 'Patient' }}</strong><small>{{ optional($appointment->patient)->email }}</small></div></div></td><td><strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y') }}</strong><small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</small></td><td>{{ $appointment->reason ?: 'General consultation' }}</td><td>{{ $appointment->current_medications ?: 'No medication notes' }}</td></tr>@empty<tr><td colspan="4" class="empty-cell">Your appointment queue is clear.</td></tr>@endforelse
    </tbody></table></div></section>
</div>
@endsection
