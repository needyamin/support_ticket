@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('E-Ticket Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Buttons for Ticket Operations -->
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('etricket.create') }}" class="btn btn-primary">
                            Create Ticket
                        </a>
                        <a href="{{ route('etricket.index') }}" class="btn btn-secondary">
                            List Tickets
                        </a>
       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
