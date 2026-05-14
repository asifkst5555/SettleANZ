/**
 * Notification System for SettleANZ Admin
 * Displays toast notifications for user actions
 */

class NotificationSystem {
    constructor(containerId = 'notificationContainer') {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            throw new Error(`Notification container with id "${containerId}" not found`);
        }
    }

    /**
     * Show a notification
     * @param {Object} options - Notification options
     * @param {string} options.type - Type: 'success', 'error', 'warning', 'info'
     * @param {string} options.title - Notification title
     * @param {string} options.message - Notification message
     * @param {number} options.duration - Auto-dismiss duration in ms (0 = no auto-dismiss)
     */
    show(options = {}) {
        const {
            type = 'info',
            title = '',
            message = '',
            duration = 5000,
        } = options;

        const notificationId = `notification-${Date.now()}`;
        const notification = document.createElement('div');
        notification.id = notificationId;
        notification.className = `notification notification--${type}`;

        const iconMap = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ',
        };

        const icon = iconMap[type] || 'ℹ';

        notification.innerHTML = `
            <div class="notification__content">
                <div class="notification__icon">${icon}</div>
                <div class="notification__body">
                    ${title ? `<strong class="notification__title">${this._escapeHtml(title)}</strong>` : ''}
                    ${message ? `<p class="notification__message">${this._escapeHtml(message)}</p>` : ''}
                </div>
                <button class="notification__close" aria-label="Close notification">&times;</button>
            </div>
        `;

        // Close button handler
        notification.querySelector('.notification__close').addEventListener('click', () => {
            this._dismissNotification(notificationId);
        });

        this.container.appendChild(notification);

        // Trigger animation
        requestAnimationFrame(() => {
            notification.classList.add('is-visible');
        });

        // Auto-dismiss
        if (duration > 0) {
            setTimeout(() => {
                this._dismissNotification(notificationId);
            }, duration);
        }

        return notificationId;
    }

    /**
     * Show success notification
     */
    success(title = '', message = '', duration = 5000) {
        return this.show({ type: 'success', title, message, duration });
    }

    /**
     * Show error notification
     */
    error(title = '', message = '', duration = 7000) {
        return this.show({ type: 'error', title, message, duration });
    }

    /**
     * Show warning notification
     */
    warning(title = '', message = '', duration = 6000) {
        return this.show({ type: 'warning', title, message, duration });
    }

    /**
     * Show info notification
     */
    info(title = '', message = '', duration = 5000) {
        return this.show({ type: 'info', title, message, duration });
    }

    /**
     * Dismiss a notification
     */
    _dismissNotification(notificationId) {
        const notification = document.getElementById(notificationId);
        if (!notification) return;

        notification.classList.remove('is-visible');
        setTimeout(() => {
            notification.remove();
        }, 300); // Match CSS transition duration
    }

    /**
     * Clear all notifications
     */
    clearAll() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach((notif) => {
            this._dismissNotification(notif.id);
        });
    }

    /**
     * Escape HTML special characters
     */
    _escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize globally
window.NotificationSystem = NotificationSystem;
let notificationSystem;

document.addEventListener('DOMContentLoaded', () => {
    try {
        notificationSystem = new NotificationSystem();
    } catch (e) {
        console.warn('Notification system not available:', e.message);
    }
});
