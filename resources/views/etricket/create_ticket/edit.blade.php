@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">

<h1>Edit Ticket</h1>

@if($errors->any())
  <div class="alert alert-danger">
    <ul>
       @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('etricket.update', $ticket->id) }}" method="POST">
  @csrf
  @method('PUT')
  <div class="form-group">
    <label for="subject">Subject</label>
    <input type="text" name="subject" class="form-control" value="{{ old('subject', $ticket->subject) }}" required>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" rows="5" class="form-control" required>{{ old('description', $ticket->description) }}</textarea>
  </div>
  <div class="form-group">
    <label for="status">Status</label>
    <select name="status" class="form-control" required>
       <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
       <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
       <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
    </select>
  </div>
  <div class="form-group">
    <label for="priority">Priority</label>
    <select name="priority" class="form-control" required>
       <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
       <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
       <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
    </select>
  </div>
  <div class="form-group">
    <label for="assigned_to">Assigned To (User ID)</label>
    <input type="number" name="assigned_to" class="form-control" value="{{ old('assigned_to', $ticket->assigned_to) }}">
  </div>
  <button type="submit" class="btn btn-primary mt-3">Update Ticket</button>
</form>


</div>
</div>
</div>
</div>
</div>
@endsection
