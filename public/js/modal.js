/**
 * Professional In-App Modal System
 * Replaces browser confirm(), alert(), and prompt() dialogs
 */

class AdminModal {
    constructor() {
        this.initializeModal();
        this.setupCloseHandlers();
        this.pendingForm = null;
    }

    initializeModal() {
        // Check if modal already exists
        if (document.getElementById('appModal')) {
            return;
        }

        const modalHTML = `
            <div id="appModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTitle">Modal Title</h2>
                        <div class="modal-divider"></div>
                    </div>
                    <p class="modal-message" id="modalMessage">Modal message goes here</p>
                    <div class="modal-actions" id="modalActions">
                        <!-- Buttons will be injected here -->
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('appModal');
    }

    setupCloseHandlers() {
        // Close modal when clicking overlay
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('is-open')) {
                this.close();
            }
        });
    }

    show({
        title = 'Message',
        message = '',
        buttons = [],
        onClose = null
    } = {}) {
        // Set title and message
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;

        // Set buttons
        const actionsContainer = document.getElementById('modalActions');
        actionsContainer.innerHTML = '';
        
        if (buttons.length === 0) {
            buttons = [{ label: 'OK', variant: 'primary', callback: () => this.close() }];
        }

        buttons.forEach((btn) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `modal-btn modal-btn-${btn.variant || 'primary'}`;
            button.textContent = btn.label;
            button.addEventListener('click', () => {
                if (btn.callback) {
                    btn.callback();
                }
                this.close();
            });
            actionsContainer.appendChild(button);
        });

        this.onClose = onClose;
        this.modal.classList.add('is-open');

        // Focus first button
        setTimeout(() => {
            const firstBtn = actionsContainer.querySelector('button');
            if (firstBtn) firstBtn.focus();
        }, 100);
    }

    close() {
        this.modal.classList.remove('is-open');
        this.pendingForm = null;
        if (this.onClose) {
            this.onClose();
        }
    }

    /**
     * Show confirmation dialog (replaces window.confirm)
     */
    confirm({
        title = 'Are you sure?',
        message = 'This action cannot be undone.',
        confirmText = 'Confirm',
        cancelText = 'Cancel',
        isDangerous = false
    } = {}) {
        return new Promise((resolve) => {
            this.show({
                title,
                message,
                buttons: [
                    {
                        label: cancelText,
                        variant: 'secondary',
                        callback: () => resolve(false)
                    },
                    {
                        label: confirmText,
                        variant: isDangerous ? 'danger' : 'primary',
                        callback: () => resolve(true)
                    }
                ]
            });
        });
    }

    /**
     * Show alert dialog (replaces window.alert)
     */
    alert({ title = 'Alert', message = '' } = {}) {
        return new Promise((resolve) => {
            this.show({
                title,
                message,
                buttons: [
                    {
                        label: 'OK',
                        variant: 'primary',
                        callback: () => resolve()
                    }
                ]
            });
        });
    }

    /**
     * Show success message
     */
    success({ title = 'Success!', message = '' } = {}) {
        return this.alert({ title, message });
    }

    /**
     * Show error message
     */
    error({ title = 'Error', message = '' } = {}) {
        return this.alert({ title, message });
    }

    /**
     * Handle form deletion confirmation
     */
    confirmDelete({ form, message = '', actionType = 'item' } = {}) {
        const typeMessages = {
            'blog': 'Are you sure you want to delete this blog post?',
            'lead': 'Are you sure you want to delete this lead?',
            'listing': 'Are you sure you want to delete this directory listing?',
            'item': 'Are you sure you want to delete this item?'
        };

        const finalMessage = message || typeMessages[actionType] || typeMessages['item'];
        const typeTitle = {
            'blog': 'Delete blog post?',
            'lead': 'Delete lead?',
            'listing': 'Delete directory listing?',
            'item': 'Delete item?'
        };

        this.confirm({
            title: typeTitle[actionType] || 'Delete?',
            message: finalMessage,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            isDangerous: true
        }).then((confirmed) => {
            if (confirmed && form) {
                // Bypass the form submission check by directly submitting
                this.pendingForm = null;
                form.submit();
            }
        });

        return false;
    }
}

// Initialize modal system
const adminModal = new AdminModal();

// Override global functions
window.showConfirm = function(message, onConfirm, onCancel) {
    adminModal.confirm({
        title: 'Confirm Action',
        message: message,
        isDangerous: message.toLowerCase().includes('delete')
    }).then((confirmed) => {
        if (confirmed && onConfirm) {
            onConfirm();
        } else if (!confirmed && onCancel) {
            onCancel();
        }
    });
};

window.showAlert = function(message, type = 'info') {
    adminModal.alert({
        title: type === 'error' ? 'Error' : type === 'success' ? 'Success' : 'Message',
        message: message
    });
};

/**
 * Handle form deletion with modal confirmation
 * Usage: onsubmit="return confirmDelete(this, 'blog')"
 */
window.confirmDelete = function(form, actionType = 'item', message = '') {
    adminModal.confirmDelete({ form, message, actionType });
    return false;
};

// Make adminModal globally available
window.adminModal = adminModal;
