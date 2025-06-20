@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-people me-2"></i> User Management
        </h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> New User
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Group</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->group) }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
