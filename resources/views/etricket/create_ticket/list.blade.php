@extends('layouts.app')

@section('content')
@php
    $isAdmin = Auth::check() && (strtolower(trim(Auth::user()->group)) === 'admin');
    $canCreate = Auth::check() && Auth::user()->isAdminOrModerator();
@endphp

<div class="container py-4">

{{-- Debug: Show current user group --}}
{{-- @if(Auth::check())
    <div class="alert alert-info small">Your group: "{{ Auth::user()->group }}"</div>
@endif --}}

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">
                    <i class="fas fa-list me-2"></i> Support Tickets
                </h2>
                <div class="d-flex align-items-center gap-2">
                    @if($canCreate)
                        <a href="{{ route('etricket.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-1"></i> New Ticket
                        </a>
                    @endif
                    @if($isAdmin)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if($tickets->count())
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tickets-table" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Last Updated</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>#{{ $ticket->id }}</td>
                                            <td>
                                                <span class="fw-semibold">{{ $ticket->subject }}</span>
                                                <div class="text-muted small">{{ Str::limit($ticket->description, 50) }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $ticket->status == 'open' ? 'info' : ($ticket->status == 'pending' ? 'warning' : 'secondary') }} text-dark">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-user me-1 text-muted"></i>{{ $ticket->user->name }}
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $ticket->updated_at->diffForHumans() }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('etricket.show', $ticket->id) }}" class="btn btn-sm btn-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($isAdmin)
                                                        <a href="{{ route('etricket.edit', $ticket->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="{{ route('etricket.destroy', $ticket->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')" type="submit" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    {{-- Remove Laravel pagination links, DataTables will handle pagination --}}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <p class="h5 text-muted">No tickets found.</p>
                    @if($canCreate)
                        <a href="{{ route('etricket.create') }}" class="btn btn-success mt-3">
                            Create Your First Ticket
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tickets-table').DataTable({
        "order": [[0, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": -1 }
        ],
        "language": {
            "search": "<i class='bi bi-search'></i> _INPUT_",
            "paginate": {
                "previous": "<i class='bi bi-chevron-left'></i>",
                "next": "<i class='bi bi-chevron-right'></i>"
            }
        }
    });
});
</script>
@endpush
