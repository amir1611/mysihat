@extends('layouts.patient.patient-layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Change Password</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('patient.change.password.update') }}" method="POST" class="bg-light p-4 rounded shadow">
        @csrf
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Change Password</button>
    </form>
</div>
@endsection
