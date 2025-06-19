@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-4 text-center">
                    <h2 class="mb-0 fw-bold">
                        <i class="bi bi-ticket-perforated me-2"></i> E-Ticket Dashboard
                    </h2>
                    <p class="mb-0 mt-2">Welcome to your support ticket system. Manage, create, and track tickets easily.</p>
                </div>
                <div class="card-body bg-light">
                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif
                    <div class="row g-4 justify-content-center mt-2">
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm text-center p-4">
                                <i class="bi bi-plus-circle fs-1 text-primary mb-3"></i>
                                <h5 class="fw-bold mb-2">Create Ticket</h5>
                                <p class="text-muted mb-3">Open a new support ticket for your issue or request.</p>
                                <a href="{{ route('etricket.create') }}" class="btn btn-primary w-100">Create Ticket</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm text-center p-4">
                                <i class="bi bi-list-task fs-1 text-secondary mb-3"></i>
                                <h5 class="fw-bold mb-2">List Tickets</h5>
                                <p class="text-muted mb-3">View and manage all your submitted tickets in one place.</p>
                                <a href="{{ route('etricket.index') }}" class="btn btn-outline-secondary w-100">List Tickets</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
