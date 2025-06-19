@extends('layouts.app')

@section('content')
<div class="bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="rounded-4 shadow mb-4 p-4 position-relative" style="background: linear-gradient(90deg, #4f8cff 0%, #6dd5ed 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-white mb-1">
                                <i class="bi bi-ticket-perforated me-2"></i> Ticket #{{ $ticket->id }}
                            </h2>
                            <div class="text-white-50 small">Created {{ $ticket->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('etricket.index') }}" class="btn btn-outline-light" data-bs-toggle="tooltip" title="Back to ticket list">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card shadow rounded-4 border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pb-0 rounded-top-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h4 mb-0 fw-bold">{{ $ticket->subject }}</h3>
                            <div>
                                <span class="badge bg-{{ $ticket->status == 'open' ? 'info' : ($ticket->status == 'pending' ? 'warning' : 'secondary') }} text-dark me-2 px-3 py-2 fs-6" data-bs-toggle="tooltip" title="Status">
                                    <i class="bi bi-circle-fill me-1"></i>{{ ucfirst($ticket->status) }}
                                </span>
                                <span class="badge bg-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'success') }} px-3 py-2 fs-6" data-bs-toggle="tooltip" title="Priority">
                                    <i class="bi bi-flag-fill me-1"></i>{{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <p class="mb-4 fs-5">{{ $ticket->description }}</p>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>
                                    <span class="fw-semibold">Created By:</span> {{ optional($ticket->user)->name }}
                                </p>
                            </div>
                            @if($ticket->assignedToUser)
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="bi bi-person-check-fill me-2 text-success"></i>
                                    <span class="fw-semibold">Assigned To:</span> {{ $ticket->assignedToUser->name }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="ticket-section mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-chat-dots me-2"></i> Replies
                        </h4>
                        <div class="flex-grow-1 border-bottom ms-3" style="height:2px;"></div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif
                    @forelse($ticket->replies as $reply)
                        <div class="card mb-3 border-0 bg-white shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-start">
                                <div class="me-3">
                                    <div class="rounded-circle bg-gradient text-white d-flex align-items-center justify-content-center shadow" style="width: 48px; height: 48px; font-size: 1.3rem; background: linear-gradient(135deg, #4f8cff 0%, #6dd5ed 100%);">
                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-6">
                                            {{ $reply->user->name }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="bi bi-clock me-1"></i> {{ $reply->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="fs-6">{{ $reply->message }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No replies yet.</div>
                    @endforelse
                    <div class="card mt-4 border-0 shadow rounded-3">
                        <div class="card-body">
                            <h5 class="mb-3 fw-bold">Add a Reply</h5>
                            @if($errors->has('message'))
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first('message') }}
                                </div>
                            @endif
                            <form action="{{ route('etricket.addReply', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="message" rows="4" class="form-control" placeholder="Type your reply here..." required>{{ old('message') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary shadow">
                                    <i class="bi bi-send me-1"></i> Submit Reply
                                </button>
                            </form>
                        </div>
                    </div>
                    <button class="btn btn-primary rounded-circle shadow position-fixed d-none d-md-flex align-items-center justify-content-center" style="bottom: 40px; right: 40px; width: 60px; height: 60px; z-index: 1050;" data-bs-toggle="modal" data-bs-target="#replyModal" title="Quick Reply">
                        <i class="bi bi-chat-dots fs-3"></i>
                    </button>
                    <!-- Modal for quick reply (optional, can be implemented if needed) -->
                </div>

                <div class="ticket-section">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-paperclip me-2"></i> Attachments
                        </h4>
                        <div class="flex-grow-1 border-bottom ms-3" style="height:2px;"></div>
                    </div>
                    @forelse($ticket->attachments as $attachment)
                        <div class="d-flex align-items-center mb-2 ps-2">
                            <i class="bi bi-file-earmark-text attachment-icon fs-5 text-primary"></i>
                            <a href="{{ asset($attachment->file_path) }}" target="_blank" class="text-decoration-none ms-2 fw-semibold">
                                Attachment #{{ $attachment->id }}
                            </a>
                        </div>
                    @empty
                        <div class="text-muted">No attachments yet.</div>
                    @endforelse
                    <div class="card mt-4 border-0 shadow rounded-3">
                        <div class="card-body">
                            <h5 class="mb-3 fw-bold">Add an Attachment</h5>
                            @if($errors->has('attachment'))
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first('attachment') }}
                                </div>
                            @endif
                            <form action="{{ route('etricket.addAttachment', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="attachment" class="form-label custom-file-label btn btn-outline-primary">
                                        <i class="bi bi-upload me-1"></i> Choose File
                                        <input type="file" name="attachment" id="attachment" class="custom-file-input" required>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary shadow">
                                    <i class="bi bi-upload me-1"></i> Upload Attachment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
