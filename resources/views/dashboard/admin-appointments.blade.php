@extends('layouts.admin.admin-layout')

@section('content')
<div class="health-dashboard admin-workspace">
    <section class="dashboard-hero admin-hero compact-hero">
        <div><span class="eyebrow">OPERATIONS</span><h1>Appointment activity</h1><p>Review the consultation schedule across MySihat’s care network.</p></div>
        <a class="primary-action" href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Overview</a>
    </section>
    <section class="panel data-panel">
        <div class="panel-heading"><div><span class="eyebrow">SCHEDULE</span><h2>All appointments</h2></div><span class="table-count">{{ $appointments->total() }} total</span></div>
        <div class="table-responsive"><table class="admin-table"><thead><tr><th>Patient</th><th>Clinician</th><th>When</th><th>Reason</th></tr></thead><tbody>
        @forelse($appointments as $appointment)<tr><td>{{ optional($appointment->patient)->name ?? 'Unknown patient' }}</td><td>{{ optional($appointment->doctor)->name ?? 'Unassigned' }}</td><td><strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y') }}</strong><small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</small></td><td>{{ $appointment->reason ?: 'General consultation' }}</td></tr>@empty<tr><td colspan="4" class="empty-cell">No appointments have been scheduled yet.</td></tr>@endforelse
        </tbody></table></div>
        <div class="pagination-wrap">{{ $appointments->links() }}</div>
    </section>
</div>
@endsection
