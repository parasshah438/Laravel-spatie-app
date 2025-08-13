@extends('admin.layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('top-buttons')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userStatsModal">
        <i class="fas fa-chart-bar"></i> Statistics
    </button>
    <button type="button" class="btn btn-outline-success" onclick="refreshPage()">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="mb-0">{{ number_format($totalUsersCount) }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Online Now</h5>
                        <h2 class="mb-0">{{ number_format($onlineUsersCount) }}</h2>
                        <small class="text-light">Active in last 5 min</small>
                    </div>
                    <i class="fas fa-circle fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Joined Today</h5>
                        <h2 class="mb-0">{{ number_format($todayRegistrations) }}</h2>
                    </div>
                    <i class="fas fa-user-plus fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Login Rate</h5>
                        <h2 class="mb-0">{{ $totalUsersCount > 0 ? round(($onlineUsersCount / $totalUsersCount) * 100, 1) : 0 }}%</h2>
                    </div>
                    <i class="fas fa-percentage fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title mb-0">User Management</h5>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search users..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm active" onclick="filterByStatus('all')">All</button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterByStatus('online')">Online</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterByStatus('offline')">Offline</button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>Status</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Login Info</th>
                        <th>Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr data-status="{{ $user->isOnline() ? 'online' : 'offline' }}">
                        <td>
                            @if($user->isOnline())
                                <span class="badge bg-success">
                                    <i class="fas fa-circle"></i> Online
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-circle"></i> Offline
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-initial bg-primary rounded-circle">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <br><small class="text-success">
                                    <i class="fas fa-check-circle"></i> Verified
                                </small>
                            @else
                                <br><small class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Unverified
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="badge bg-info me-1">{{ $role->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No roles assigned</span>
                            @endif
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <small>
                                    <strong>Last Login:</strong><br>
                                    {{ $user->last_login_at->diffForHumans() }}<br>
                                    <span class="text-muted">{{ $user->last_login_at->format('M j, Y H:i') }}</span><br>
                                    
                                    @if($user->last_login_ip)
                                        <strong>IP:</strong> {{ $user->last_login_ip }}<br>
                                    @endif
                                    
                                    <strong>Logins:</strong> {{ $user->login_count ?? 0 }}
                                </small>
                            @else
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </td>
                        <td>
                            @if($user->last_activity_at)
                                <small>
                                    <strong>Last Activity:</strong><br>
                                    {{ $user->last_activity_at->diffForHumans() }}<br>
                                    <span class="text-muted">{{ $user->last_activity_at->format('M j, Y H:i') }}</span>
                                </small>
                            @else
                                <span class="text-muted">No activity</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                <strong>Joined:</strong> {{ $user->created_at->format('M j, Y') }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="viewUser({{ $user->id }})">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editUser({{ $user->id }})">
                                        <i class="fas fa-edit me-2"></i>Edit User
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="viewLoginHistory({{ $user->id }})">
                                        <i class="fas fa-history me-2"></i>Login History
                                    </a></li>
                                    @if($user->isOnline())
                                    <li><a class="dropdown-item text-warning" href="#" onclick="forceLogout({{ $user->id }})">
                                        <i class="fas fa-sign-out-alt me-2"></i>Force Logout
                                    </a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users found</h5>
                            <p class="text-muted">There are no users to display.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="pagination-info">
                <span class="text-muted">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} 
                    of {{ $users->total() }} users
                </span>
            </div>
            <div class="pagination-links">
                {{ $users->links('pagination.bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh page every 30 seconds for real-time updates
setInterval(function() {
    if (document.visibilityState === 'visible') {
        // Only refresh if no modals are open
        if (!document.querySelector('.modal.show')) {
            window.location.reload();
        }
    }
}, 30000);

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Filter functionality
function filterByStatus(status) {
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    const rows = document.querySelectorAll('#usersTable tbody tr[data-status]');
    
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === status ? '' : 'none';
        }
    });
}

// Action functions
function viewUser(userId) {
    alert('View user details for ID: ' + userId + ' - Feature coming soon!');
}

function editUser(userId) {
    alert('Edit user for ID: ' + userId + ' - Feature coming soon!');
}

function viewLoginHistory(userId) {
    alert('View login history for user ID: ' + userId + ' - Feature coming soon!');
}

function forceLogout(userId) {
    if (confirm('Are you sure you want to force logout this user?')) {
        alert('Force logout for user ID: ' + userId + ' - Feature coming soon!');
    }
}

function refreshPage() {
    window.location.reload();
}
</script>
@endpush

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

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.opacity-75 {
    opacity: 0.75;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

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
                    