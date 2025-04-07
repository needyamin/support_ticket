@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">

<a href="{{ route('etricket.create') }}" class="btn btn-sm btn-primary">Create New Ticket</a>

<div class="mb-3"><h1>E-Tickets</h1></div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($tickets->count())
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Subject</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Created By</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($tickets as $ticket)
        <tr>
          <td>{{ $ticket->id }}</td>
          <td>{{ $ticket->subject }}</td>
          <td>{{ $ticket->status }}</td>
          <td>{{ $ticket->priority }}</td>
          <td>{{ $ticket->user->name }}</td>
          <td>
            <a href="{{ route('etricket.show', $ticket->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('etricket.edit', $ticket->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('etricket.destroy', $ticket->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $tickets->links() }}
@else
  <p>No tickets found.</p>
@endif
@endsection

</div>
</div>
</div>
</div>
</div>
