@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="bi bi-person-plus me-2"></i> Create New User
    </h2>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="group" class="form-label">Group</label>
                    <select class="form-select" id="group" name="group" required>
                        <option value="user" @if(old('group')=='user') selected @endif>User</option>
                        <option value="moderator" @if(old('group')=='moderator') selected @endif>Moderator</option>
                        <option value="admin" @if(old('group')=='admin') selected @endif>Admin</option>
                    </select>
                    @error('group')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
