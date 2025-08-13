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
                    
                    <!-- Pagination and Info -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <!-- Left side: Pagination info -->
                        <div class="pagination-info">
                            <span class="text-muted">
                                Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} 
                                of {{ $activities->total() }} results
                            </span>
                        </div>
                        
                        <!-- Right side: Pagination links -->
                        <div class="pagination-links">
                            {{ $activities->appends(request()->query())->links('pagination.bootstrap-5') }}
                        </div>
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

/* Custom Pagination Styles */
.pagination {
    --bs-pagination-padding-x: 0.75rem;
    --bs-pagination-padding-y: 0.375rem;
    --bs-pagination-font-size: 1rem;
    --bs-pagination-color: #6c757d;
    --bs-pagination-bg: #fff;
    --bs-pagination-border-width: 1px;
    --bs-pagination-border-color: #dee2e6;
    --bs-pagination-border-radius: 0.375rem;
    --bs-pagination-hover-color: #495057;
    --bs-pagination-hover-bg: #e9ecef;
    --bs-pagination-hover-border-color: #dee2e6;
    --bs-pagination-focus-color: #495057;
    --bs-pagination-focus-bg: #e9ecef;
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: #fff;
    --bs-pagination-disabled-border-color: #dee2e6;
}

.page-link {
    position: relative;
    display: block;
    color: var(--bs-pagination-color);
    text-decoration: none;
    background-color: var(--bs-pagination-bg);
    border: var(--bs-pagination-border-width) solid var(--bs-pagination-border-color);
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.page-link:hover {
    z-index: 2;
    color: var(--bs-pagination-hover-color);
    background-color: var(--bs-pagination-hover-bg);
    border-color: var(--bs-pagination-hover-border-color);
}

.page-link:focus {
    z-index: 3;
    color: var(--bs-pagination-focus-color);
    background-color: var(--bs-pagination-focus-bg);
    outline: 0;
    box-shadow: var(--bs-pagination-focus-box-shadow);
}

.page-link i {
    font-size: 0.875rem;
}

.page-item:not(:first-child) .page-link {
    margin-left: -1px;
}

.page-item.active .page-link {
    z-index: 3;
    color: var(--bs-pagination-active-color);
    background-color: var(--bs-pagination-active-bg);
    border-color: var(--bs-pagination-active-border-color);
}

.page-item.disabled .page-link {
    color: var(--bs-pagination-disabled-color);
    pointer-events: none;
    background-color: var(--bs-pagination-disabled-bg);
    border-color: var(--bs-pagination-disabled-border-color);
}

.page-item:first-child .page-link {
    border-top-left-radius: var(--bs-pagination-border-radius);
    border-bottom-left-radius: var(--bs-pagination-border-radius);
}

.page-item:last-child .page-link {
    border-top-right-radius: var(--bs-pagination-border-radius);
    border-bottom-right-radius: var(--bs-pagination-border-radius);
}

.sr-only {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

/* Pagination Layout Styles */
.pagination-info {
    font-size: 0.875rem;
    color: #6c757d;
}

.pagination-links .pagination {
    margin-bottom: 0;
}

/* Responsive pagination layout */
@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center {
        flex-direction: column-reverse;
        gap: 1rem;
    }
    
    .pagination-info {
        text-align: center;
        width: 100%;
    }
    
    .pagination-links {
        width: 100%;
        display: flex;
        justify-content: center;
    }
}
</style>
@endpush
@endsection
