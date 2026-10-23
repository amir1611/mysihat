@extends('layouts.admin.admin-layout')

@section('content')
<div class="health-dashboard admin-workspace">
    <section class="dashboard-hero admin-hero compact-hero">
        <div><span class="eyebrow">ADMINISTRATION</span><h1>People & access</h1><p>Review the people using MySihat and their role in the care network.</p></div>
        <a class="primary-action" href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Overview</a>
    </section>
    <section class="panel data-panel">
        <div class="panel-heading"><div><span class="eyebrow">DIRECTORY</span><h2>All members</h2></div><span class="table-count">{{ $users->total() }} total</span></div>
        <div class="table-responsive"><table class="admin-table"><thead><tr><th>Member</th><th>Role</th><th>Contact</th><th>Joined</th></tr></thead><tbody>
        @foreach($users as $user)<tr><td><div class="table-person"><span class="member-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><div><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></div></div></td><td><span class="role-badge">{{ ['Patient', 'Administrator', 'Doctor'][$user->type] ?? 'Member' }}</span></td><td>{{ $user->phone_number ?: '—' }}</td><td>{{ $user->created_at->format('d M Y') }}</td></tr>@endforeach
        </tbody></table></div>
        <div class="pagination-wrap">{{ $users->links() }}</div>
    </section>
</div>
@endsection
