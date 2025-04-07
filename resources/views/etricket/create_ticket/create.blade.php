@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">

<h1>Create New Ticket</h1>

@if($errors->any())
  <div class="alert alert-danger">
    <ul>
       @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('etricket.store') }}" method="POST">
  @csrf
  <div class="form-group">
    <label for="subject">Subject</label>
    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
  </div>
  <div class="form-group">
    <label for="priority">Priority</label>
    <select name="priority" class="form-control">
       <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
       <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
       <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary mt-3">Submit Ticket</button>
</form>

</div>
</div>
</div>
</div>
</div>

@endsection
