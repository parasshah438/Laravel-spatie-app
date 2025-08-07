/**
 * Real-time Dashboard Updates with Pusher
 */

class DashboardRealTime {
    constructor(options = {}) {
        this.options = {
            pusherKey: options.pusherKey || 'local-app-key',
            pusherCluster: options.pusherCluster || 'mt1',
            guard: options.guard || 'web',
            updateStatsUrl: options.updateStatsUrl || '/home/stats',
            ...options
        };
        
        this.init();
    }

    init() {
        // Initialize Pusher
        if (typeof Pusher !== 'undefined') {
            this.initPusher();
        } else {
            console.warn('Pusher not loaded, falling back to polling');
            this.startPolling();
        }
    }

    initPusher() {
        try {
            this.pusher = new Pusher(this.options.pusherKey, {
                cluster: this.options.pusherCluster,
                encrypted: true
            });

            // Subscribe to general dashboard channel
            this.dashboardChannel = this.pusher.subscribe('dashboard');
            this.guardChannel = this.pusher.subscribe(`dashboard.${this.options.guard}`);

            // Listen for activity updates
            this.dashboardChannel.bind('activity.logged', (data) => {
                this.handleActivityUpdate(data);
            });

            this.guardChannel.bind('activity.logged', (data) => {
                this.handleActivityUpdate(data);
            });

            // Listen for stats updates
            this.dashboardChannel.bind('stats.updated', (data) => {
                this.handleStatsUpdate(data);
            });

            this.guardChannel.bind('stats.updated', (data) => {
                this.handleStatsUpdate(data);
            });

            console.log('Real-time dashboard initialized with Pusher');
        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
            this.startPolling();
        }
    }

    startPolling() {
        // Fallback to polling every 30 seconds
        this.pollingInterval = setInterval(() => {
            this.fetchStats();
        }, 30000);
        
        console.log('Real-time dashboard initialized with polling');
    }

    handleActivityUpdate(data) {
        // Update activity feed
        this.addActivityToFeed(data);
        
        // Update activity counters
        this.updateActivityCounters();
        
        // Show notification
        this.showNotification(`New activity: ${data.description}`, 'info');
    }

    handleStatsUpdate(data) {
        if (data.guard === this.options.guard || !data.guard) {
            this.updateStatsDisplay(data.stats);
        }
    }

    addActivityToFeed(activity) {
        const feedContainer = document.querySelector('#activity-feed');
        if (!feedContainer) return;

        const activityHtml = this.createActivityHtml(activity);
        feedContainer.insertAdjacentHTML('afterbegin', activityHtml);

        // Remove old activities (keep only latest 10)
        const activities = feedContainer.querySelectorAll('.activity-item');
        if (activities.length > 10) {
            for (let i = 10; i < activities.length; i++) {
                activities[i].remove();
            }
        }

        // Add fade-in animation
        const newActivity = feedContainer.querySelector('.activity-item');
        newActivity.classList.add('animate__animated', 'animate__fadeInDown');
    }

    createActivityHtml(activity) {
        return `
            <div class="activity-item border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">${activity.description}</h6>
                        <p class="text-muted small mb-1">
                            by ${activity.causer_name}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${activity.created_at}
                        </small>
                    </div>
                    <span class="badge bg-primary">New</span>
                </div>
            </div>
        `;
    }

    updateActivityCounters() {
        // Increment activity counters if they exist
        const counters = document.querySelectorAll('[data-counter="activities"]');
        counters.forEach(counter => {
            const currentValue = parseInt(counter.textContent) || 0;
            counter.textContent = currentValue + 1;
            counter.classList.add('animate__animated', 'animate__pulse');
        });
    }

    updateStatsDisplay(stats) {
        Object.keys(stats).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                const oldValue = element.textContent;
                const newValue = stats[key];
                
                if (oldValue !== newValue.toString()) {
                    element.textContent = newValue;
                    element.classList.add('animate__animated', 'animate__flipInX');
                    
                    // Remove animation class after animation
                    setTimeout(() => {
                        element.classList.remove('animate__animated', 'animate__flipInX');
                    }, 1000);
                }
            }
        });
    }

    fetchStats() {
        fetch(this.options.updateStatsUrl)
            .then(response => response.json())
            .then(data => {
                if (data.system_stats) {
                    this.updateStatsDisplay(data.system_stats);
                }
                if (data.stats) {
                    this.updateStatsDisplay(data.stats);
                }
                if (data.recent_activities) {
                    this.updateActivityFeed(data.recent_activities);
                }
            })
            .catch(error => {
                console.error('Failed to fetch stats:', error);
            });
    }

    updateActivityFeed(activities) {
        const feedContainer = document.querySelector('#activity-feed');
        if (!feedContainer) return;

        feedContainer.innerHTML = '';
        activities.forEach(activity => {
            const activityHtml = this.createActivityHtml(activity);
            feedContainer.insertAdjacentHTML('beforeend', activityHtml);
        });
    }

    showNotification(message, type = 'info') {
        // Create notification if notification container exists
        const notificationContainer = document.querySelector('#notification-container');
        if (!notificationContainer) return;

        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        notificationContainer.appendChild(notification);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    destroy() {
        if (this.pusher) {
            this.pusher.disconnect();
        }
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
    }
}

// Auto-initialize if window.dashboardConfig exists
document.addEventListener('DOMContentLoaded', function() {
    if (window.dashboardConfig) {
        window.dashboardRealTime = new DashboardRealTime(window.dashboardConfig);
    }
});

// Export for manual initialization
window.DashboardRealTime = DashboardRealTime;
