@extends('admin.layouts.app')

@section('title', 'System Activity Log')
@section('page-title', 'System Activity Log')

@section('top-buttons')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-outline-secondary {{ $filter === 'all' ? 'active' : '' }}" 
            onclick="location.href='{{ route('admin.activities.index') }}'">
        All Activities
    </button>
    <button type="button" class="btn btn-outline-secondary {{ $filter === 'today' ? 'active' : '' }}" 
            onclick="location.href='{{ route('admin.activities.index', ['filter' => 'today']) }}'">
        Today
    </button>
    <button type="button" class="btn btn-outline-secondary {{ $filter === 'week' ? 'active' : '' }}" 
            onclick="location.href='{{ route('admin.activities.index', ['filter' => 'week']) }}'">
        This Week
    </button>
    <button type="button" class="btn btn-outline-secondary {{ $filter === 'month' ? 'active' : '' }}" 
            onclick="location.href='{{ route('admin.activities.index', ['filter' => 'month']) }}'">
        This Month
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    System Activity Log
                    <span class="badge bg-primary ms-2">{{ $activities->total() }} total</span>
                </h5>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Subject</th>
                                    <th>IP Address</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            {{ $activity->created_at->format('M j, Y') }}<br>
                                            {{ $activity->created_at->format('g:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($activity->causer)
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $userType = class_basename($activity->causer_type);
                                                    $badgeColor = match($userType) {
                                                        'Admin' => 'bg-danger',
                                                        'User' => 'bg-primary',
                                                        'Customer' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-initial {{ $badgeColor }} rounded-circle">
                                                        {{ strtoupper(substr($activity->causer->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>{{ $activity->causer->name }}</strong>
                                                    <span class="badge {{ $badgeColor }} ms-2">{{ $userType }}</span><br>
                                                    <small class="text-muted">{{ $activity->causer->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-cog me-1"></i>
                                                System
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if(str_contains($activity->description, 'Created')) $badgeClass = 'bg-success';
                                            elseif(str_contains($activity->description, 'Updated')) $badgeClass = 'bg-warning';
                                            elseif(str_contains($activity->description, 'Deleted')) $badgeClass = 'bg-danger';
                                            elseif(str_contains($activity->description, 'login')) $badgeClass = 'bg-info';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $activity->description }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($activity->subject)
                                            <div>
                                                <strong>{{ class_basename($activity->subject_type) }}</strong><br>
                                                <small class="text-muted">ID: {{ $activity->subject_id }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($activity->properties['ip_address']))
                                            <small class="text-muted">{{ $activity->properties['ip_address'] }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->properties && count($activity->properties) > 0)
                                            <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#details-{{ $activity->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <div class="collapse mt-2" id="details-{{ $activity->id }}">
                                                <div class="card card-body bg-light">
                                                    <pre><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $activities->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Activities Found</h5>
                        <p class="text-muted">No admin activities have been recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}
</style>
@endpush
@endsection
