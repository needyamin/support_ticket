@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">

<h1>Ticket Details</h1>

<div class="card mb-3">
  <div class="card-header">
    Ticket #{{ $ticket->id }} - {{ $ticket->subject }}
  </div>
  <div class="card-body">
    <p><strong>Description:</strong> {{ $ticket->description }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>
    <p><strong>Priority:</strong> {{ $ticket->priority }}</p>
    <p><strong>Created By:</strong> {{ optional($ticket->user)->name }}</p>
    @if($ticket->assignedToUser)
      <p><strong>Assigned To:</strong> {{ $ticket->assignedToUser->name }}</p>
    @endif
  </div>
</div>

<!-- Replies Section -->
<h3>Replies</h3>
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@foreach($ticket->replies as $reply)
  <div class="card mb-2">
    <div class="card-header">
      {{ $reply->user->name }} replied on {{ $reply->created_at->format('Y-m-d H:i') }}
    </div>
    <div class="card-body">
      {{ $reply->message }}
    </div>
  </div>
@endforeach

<!-- Reply Form -->
<h4>Add a Reply</h4>
@if($errors->has('message'))
  <div class="alert alert-danger">{{ $errors->first('message') }}</div>
@endif
<form action="{{ route('etricket.addReply', $ticket->id) }}" method="POST">
  @csrf
  <div class="form-group">
    <textarea name="message" rows="4" class="form-control" required>{{ old('message') }}</textarea>
  </div>
  <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
</form>

<!-- Attachments Section -->
<h3 class="mt-4">Attachments</h3>
@foreach($ticket->attachments as $attachment)
  <div>
    <a href="{{ asset($attachment->file_path) }}" target="_blank">Attachment #{{ $attachment->id }}</a>

  </div>
@endforeach

<!-- Attachment Form -->
<h4 class="mt-3">Add an Attachment</h4>
@if($errors->has('attachment'))
  <div class="alert alert-danger">{{ $errors->first('attachment') }}</div>
@endif
<form action="{{ route('etricket.addAttachment', $ticket->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <input type="file" name="attachment" class="form-control-file" required>
  </div>
  <button type="submit" class="btn btn-primary mt-2">Upload Attachment</button>
</form>

</div>
</div>
</div>
</div>
</div>

@endsection
