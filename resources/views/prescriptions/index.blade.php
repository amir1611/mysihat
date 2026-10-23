@extends('layouts.doctor.doctor-layout')

@section('content')
<div class="health-dashboard">
    <section class="dashboard-hero doctor-hero compact-hero"><div><span class="eyebrow">CLINICAL PRESCRIBING</span><h1>Approve patient medicine access.</h1><p>Approved items become available only to that patient in the MySihat marketplace.</p></div></section>
    @if(session('success'))<div class="market-notice"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
    <section class="dashboard-grid prescription-layout">
        <article class="panel"><div class="panel-heading"><div><span class="eyebrow">NEW APPROVAL</span><h2>Create prescription</h2></div></div>
            <form method="POST" action="{{ route('doctor.prescriptions.store') }}" class="prescription-form">@csrf
                <label>Patient<select name="patient_id" required><option value="">Choose patient</option>@foreach($patients as $patient)<option value="{{ $patient->id }}">{{ $patient->name }} — {{ $patient->email }}</option>@endforeach</select></label>
                <label>Prescription medicine<select name="medicine_id" required><option value="">Choose medicine</option>@foreach($medicines as $id => $medicine)<option value="{{ $id }}">{{ $medicine['name'] }} · RM {{ number_format($medicine['price'], 2) }}</option>@endforeach</select></label>
                <div class="form-split"><label>Approved quantity<input type="number" name="quantity" min="1" max="10" value="1" required></label><label>Expires on<input type="date" name="expires_at" min="{{ today()->addDay()->toDateString() }}" required></label></div>
                <label>Clinical note <textarea name="notes" rows="3" placeholder="Optional note visible in the approval record"></textarea></label>
                <button class="checkout-button" type="submit">Approve marketplace access <i class="fas fa-check"></i></button>
            </form>
        </article>
        <article class="panel care-nudge"><span class="eyebrow">SAFETY</span><h2>Patient-specific access</h2><p>Only the named patient can add an approved medicine to their cart, up to the approved quantity and expiry date.</p></article>
    </section>
    <section class="panel data-panel"><div class="panel-heading"><div><span class="eyebrow">YOUR APPROVALS</span><h2>Recent prescriptions</h2></div></div>
        <div class="table-responsive"><table class="admin-table"><thead><tr><th>Patient</th><th>Medicine</th><th>Quantity</th><th>Expires</th><th>Status</th></tr></thead><tbody>@forelse($prescriptions as $prescription)<tr><td>{{ $prescription->patient->name }}</td><td>{{ $prescription->medicine_name }}</td><td>{{ $prescription->quantity }}</td><td>{{ $prescription->expires_at->format('d M Y') }}</td><td><span class="role-badge">{{ ucfirst($prescription->status) }}</span></td></tr>@empty<tr><td colspan="5" class="empty-cell">No prescriptions have been approved yet.</td></tr>@endforelse</tbody></table></div>
    </section>
</div>
@endsection
