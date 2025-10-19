<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['pending'] }}</h4>
                        <p class="mb-0">Pending Requests</p>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['approved'] }}</h4>
                        <p class="mb-0">Approved</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['waiting_approval'] }}</h4>
                        <p class="mb-0">Waiting My Approval</p>
                    </div>
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['rejected'] }}</h4>
                        <p class="mb-0">Rejected</p>
                    </div>
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Requests</h5>
                <a href="/requests/create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Request
                </a>
            </div>
            <div class="card-body">
                @if($myRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Amount</th>
                                    <th>Department</th>
                                    <th>Current Approver</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myRequests as $request)
                                <tr>
                                    <td>#{{ $request->id }}</td>
                                    <td>৳{{ number_format($request->amount, 2) }}</td>
                                    <td>{{ $request->department->name }}</td>
                                    <td>
                                        @if($request->current_approver_id)
                                            {{ $request->currentApprover->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/requests/{{ $request->id }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">No requests found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pending My Approval</h5>
            </div>
            <div class="card-body">
                @if($pendingApprovals->count() > 0)
                    @foreach($pendingApprovals as $request)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6>Request #{{ $request->id }}</h6>
                            <p class="mb-1">
                                <strong>Amount:</strong> ৳{{ number_format($request->amount, 2) }}<br>
                                <strong>From:</strong> {{ $request->user->name }}<br>
                                <strong>Department:</strong> {{ $request->department->name }}
                            </p>
                            <div class="d-grid gap-2">
                                <form action="/requests/{{ $request->id }}/approve" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm w-100"
                                            onclick="return confirmAction('Approve this request?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="/requests/{{ $request->id }}/reject" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm w-100"
                                            onclick="return confirmAction('Reject this request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">No pending approvals.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
