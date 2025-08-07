@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('top-buttons')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userStatsModal">
        <i class="fas fa-chart-bar"></i> Statistics
    </button>
    <a href="#" class="btn btn-success" onclick="alert('User creation will be implemented soon!')">
        <i class="fas fa-plus"></i> Add New User
    </a>
</div>
@endsection

@section('content')
<div class="card shadow">
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
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="filterByStatus('all')">All</button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterByStatus('verified')">Verified</button>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterByStatus('unverified')">Unverified</button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Email Verified</th>
                        <th>Joined Date</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="badge bg-secondary">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Unverified
                                </span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <small>{{ $user->updated_at->format('M d, Y') }}</small><br>
                            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm" onclick="viewUser({{ $user->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="toggleUserStatus({{ $user->id }})">
                                            <i class="fas fa-user-slash"></i> Toggle Status
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="resetUserPassword({{ $user->id }})">
                                            <i class="fas fa-key"></i> Reset Password
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                            <i class="fas fa-trash"></i> Delete User
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <h5>No Users Found</h5>
                                <p>No users are registered in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- User Statistics Modal -->
<div class="modal fade" id="userStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="bg-primary text-white rounded p-3">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4>{{ $users->total() }}</h4>
                            <small>Total Users</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-success text-white rounded p-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4>{{ $users->filter(function($user) { return $user->email_verified_at !== null; })->count() }}</h4>
                            <small>Verified</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-warning text-white rounded p-3">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <h4>{{ $users->filter(function($user) { return $user->email_verified_at === null; })->count() }}</h4>
                            <small>Unverified</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-info text-white rounded p-3">
                            <i class="fas fa-calendar fa-2x mb-2"></i>
                            <h4>{{ $users->filter(function($user) { return $user->created_at >= now()->subDays(30); })->count() }}</h4>
                            <small>New (30 days)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewUser(userId) {
    alert('View user details for ID: ' + userId + '\nThis feature will be implemented soon!');
}

function editUser(userId) {
    alert('Edit user for ID: ' + userId + '\nThis feature will be implemented soon!');
}

function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to toggle this user\'s status?')) {
        alert('Toggle status for user ID: ' + userId + '\nThis feature will be implemented soon!');
    }
}

function resetUserPassword(userId) {
    if (confirm('Are you sure you want to reset this user\'s password?')) {
        alert('Reset password for user ID: ' + userId + '\nThis feature will be implemented soon!');
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone!')) {
        alert('Delete user ID: ' + userId + '\nThis feature will be implemented soon!');
    }
}

function filterByStatus(status) {
    // Simple client-side filtering for demo
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else if (status === 'verified') {
            const verified = row.querySelector('.badge.bg-success');
            row.style.display = verified ? '' : 'none';
        } else if (status === 'unverified') {
            const unverified = row.querySelector('.badge.bg-warning');
            row.style.display = unverified ? '' : 'none';
        }
    });
}

// Simple search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endpush
