@extends('layouts.admin.admin-layout')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Profile</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="bg-light p-4 rounded shadow">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number', $user->phone_number) }}">
            </div>


            <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </form>
    </div>
@endsection
