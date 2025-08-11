@extends('admin.layouts.app')

@section('title', 'User Registration Analytics')
@section('page-title', 'User Registration Analytics')

@section('top-buttons')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-outline-primary {{ $dateRange === 'today' ? 'active' : '' }}" 
            onclick="setDateRange('today')">Today</button>
    <button type="button" class="btn btn-outline-primary {{ $dateRange === 'last_7_days' ? 'active' : '' }}" 
            onclick="setDateRange('last_7_days')">7 Days</button>
    <button type="button" class="btn btn-outline-primary {{ $dateRange === 'last_30_days' ? 'active' : '' }}" 
            onclick="setDateRange('last_30_days')">30 Days</button>
    <button type="button" class="btn btn-outline-primary {{ $dateRange === 'this_month' ? 'active' : '' }}" 
            onclick="setDateRange('this_month')">This Month</button>
</div>
<button type="button" class="btn btn-success ms-3" onclick="exportData()">
    <i class="fas fa-download"></i> Export Data
</button>
@endsection

@section('content')
<!-- Date Range Picker -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="dateRangeForm" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_range" class="form-label">Quick Select</label>
                        <select class="form-select" id="date_range" name="date_range">
                            <option value="custom" {{ $dateRange === 'custom' ? 'selected' : '' }}>Custom Range</option>
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="last_7_days" {{ $dateRange === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $dateRange === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $dateRange === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Users Today</h5>
                        <h2 class="mb-0">{{ number_format($analytics['users_today']) }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-75"></i>
                </div>
                <small class="mt-2 d-block">
                    Total Users: {{ number_format($analytics['total_users']) }}
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Customers Today</h5>
                        <h2 class="mb-0">{{ number_format($analytics['customers_today']) }}</h2>
                    </div>
                    <i class="fas fa-user-friends fa-3x opacity-75"></i>
                </div>
                <small class="mt-2 d-block">
                    Total Customers: {{ number_format($analytics['total_customers']) }}
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Users in Period</h5>
                        <h2 class="mb-0">{{ number_format($analytics['users_in_period']) }}</h2>
                        @if($analytics['user_growth_rate'] != 0)
                            <small class="d-flex align-items-center">
                                <i class="fas fa-{{ $analytics['user_growth_rate'] > 0 ? 'arrow-up text-success' : 'arrow-down text-danger' }} me-1"></i>
                                {{ abs($analytics['user_growth_rate']) }}%
                            </small>
                        @endif
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Customers in Period</h5>
                        <h2 class="mb-0">{{ number_format($analytics['customers_in_period']) }}</h2>
                        @if($analytics['customer_growth_rate'] != 0)
                            <small class="d-flex align-items-center">
                                <i class="fas fa-{{ $analytics['customer_growth_rate'] > 0 ? 'arrow-up text-success' : 'arrow-down text-danger' }} me-1"></i>
                                {{ abs($analytics['customer_growth_rate']) }}%
                            </small>
                        @endif
                    </div>
                    <i class="fas fa-chart-bar fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Registration Trends</h5>
                <small class="text-muted">{{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}</small>
            </div>
            <div class="card-body">
                <canvas id="registrationChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats and Recent Users -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Additional Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>This Month Statistics:</strong>
                    <ul class="list-unstyled mt-2">
                        <li><i class="fas fa-users text-primary me-2"></i> Users: {{ number_format($analytics['users_this_month']) }}</li>
                        <li><i class="fas fa-user-friends text-success me-2"></i> Customers: {{ number_format($analytics['customers_this_month']) }}</li>
                        <li><i class="fas fa-chart-line text-info me-2"></i> Activities: {{ number_format($analytics['total_activities_in_period']) }}</li>
                    </ul>
                </div>
                
                @if($analytics['most_active_day'])
                <div class="alert alert-info">
                    <strong>Most Active Day:</strong><br>
                    {{ $analytics['most_active_day']['date'] }}<br>
                    <small>{{ $analytics['most_active_day']['count'] }} {{ $analytics['most_active_day']['type'] }} registered</small>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Users</h5>
            </div>
            <div class="card-body">
                @if($recentUsers['users']->count() > 0)
                    @foreach($recentUsers['users']->take(5) as $user)
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar-sm me-2">
                            <div class="avatar-initial bg-primary rounded-circle">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <small class="fw-bold">{{ $user->name }}</small>
                            <br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <span class="badge bg-primary">User</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent users</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Customers</h5>
            </div>
            <div class="card-body">
                @if($recentUsers['customers']->count() > 0)
                    @foreach($recentUsers['customers']->take(5) as $customer)
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar-sm me-2">
                            <div class="avatar-initial bg-success rounded-circle">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <small class="fw-bold">{{ $customer->name }}</small>
                            <br>
                            <small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                        </div>
                        <span class="badge bg-success">Customer</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent customers</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart configuration
const ctx = document.getElementById('registrationChart').getContext('2d');
const chartData = @json($chartData);

const chart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255,255,255,0.1)',
                borderWidth: 1
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Date range form handling
document.getElementById('dateRangeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    window.location.href = '{{ route("admin.analytics.users") }}?' + params.toString();
});

// Quick date range selection
document.getElementById('date_range').addEventListener('change', function() {
    if (this.value !== 'custom') {
        const form = document.getElementById('dateRangeForm');
        form.querySelector('[name="date_range"]').value = this.value;
        form.submit();
    }
});

// Date picker validation
document.getElementById('start_date').addEventListener('change', function() {
    const endDate = document.getElementById('end_date');
    endDate.min = this.value;
    
    if (endDate.value && endDate.value < this.value) {
        endDate.value = this.value;
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    document.getElementById('start_date').max = this.value;
});

// Quick date range buttons
function setDateRange(range) {
    document.getElementById('date_range').value = range;
    document.getElementById('dateRangeForm').submit();
}

// Export functionality
function exportData() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    fetch(`{{ route('admin.analytics.users.export') }}?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            // Create downloadable JSON file
            const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `user_analytics_${startDate}_to_${endDate}.json`;
            a.click();
            window.URL.revokeObjectURL(url);
            
            // Show success message
            alert('Analytics data exported successfully!');
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Error exporting data. Please try again.');
        });
}

// Auto-refresh every 5 minutes if viewing today's data
@if($dateRange === 'today')
setInterval(function() {
    if (document.visibilityState === 'visible') {
        window.location.reload();
    }
}, 300000); // 5 minutes
@endif
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

#registrationChart {
    max-height: 400px;
}

.opacity-75 {
    opacity: 0.75;
}
</style>
@endpush
@endsection
