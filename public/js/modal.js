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
     * Show prompt dialog (replaces window.prompt)
     */
    prompt({ title = 'Input', message = '', value = '', placeholder = '', confirmText = 'OK', cancelText = 'Cancel' } = {}) {
        return new Promise((resolve) => {
            const inputId = 'modalPromptInput';
            const buttons = [
                {
                    label: cancelText,
                    variant: 'secondary',
                    callback: () => resolve(null)
                },
                {
                    label: confirmText,
                    variant: 'primary',
                    callback: () => {
                        const input = document.getElementById(inputId);
                        resolve(input ? input.value : null);
                    }
                }
            ];

            this.show({
                title,
                message: '',
                buttons,
                onClose: () => resolve(null)
            });

            const msgEl = document.getElementById('modalMessage');
            msgEl.innerHTML = '';
            if (message) {
                const label = document.createElement('p');
                label.textContent = message;
                msgEl.appendChild(label);
            }
            const input = document.createElement('input');
            input.id = inputId;
            input.type = 'text';
            input.className = 'modal-input';
            input.value = value;
            input.placeholder = placeholder;
            msgEl.appendChild(input);
            setTimeout(() => input.focus(), 150);
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
            'item': 'Are you sure you want to delete this item?',
            'campaign': 'Are you sure you want to delete this campaign?',
            'token': 'Are you sure you want to revoke this token?',
            'category': 'Are you sure you want to delete this category?',
            'tag': 'Are you sure you want to delete this tag?',
            'template': 'Are you sure you want to delete this template?',
            'flag': 'Are you sure you want to delete this feature flag?',
            'permission': 'Are you sure you want to delete this permission?',
            'role': 'Are you sure you want to delete this role?',
            'user': 'Are you sure you want to delete this user permanently?',
            'knowledge': 'Are you sure you want to delete this knowledge entry?',
            'review': 'Are you sure you want to delete this review?',
            'note': 'Are you sure you want to delete this note?',
            'task': 'Are you sure you want to delete this task?',
            'file': 'Are you sure you want to delete this file?',
        };

        const finalMessage = message || typeMessages[actionType] || typeMessages['item'];
        const typeTitle = {
            'blog': 'Delete blog post?',
            'lead': 'Delete lead?',
            'listing': 'Delete directory listing?',
            'item': 'Delete item?',
            'campaign': 'Delete campaign?',
            'token': 'Revoke token?',
            'category': 'Delete category?',
            'tag': 'Delete tag?',
            'template': 'Delete template?',
            'flag': 'Delete feature flag?',
            'permission': 'Delete permission?',
            'role': 'Delete role?',
            'user': 'Delete user?',
            'knowledge': 'Delete knowledge entry?',
            'review': 'Delete review?',
            'note': 'Delete note?',
            'task': 'Delete task?',
            'file': 'Delete file?',
        };

        this.confirm({
            title: typeTitle[actionType] || 'Delete?',
            message: finalMessage,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            isDangerous: true
        }).then((confirmed) => {
            if (confirmed && form) {
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

/**
 * Generic form confirmation (for send, revoke, etc.)
 * Usage: onsubmit="return confirmAction(this, { title: 'Send?', message: 'Proceed?', confirmText: 'Send' })"
 */
window.confirmAction = function(form, options = {}) {
    adminModal.confirm({
        title: options.title || 'Are you sure?',
        message: options.message || 'Proceed with this action?',
        confirmText: options.confirmText || 'Confirm',
        cancelText: options.cancelText || 'Cancel',
        isDangerous: options.isDangerous ?? false
    }).then((confirmed) => {
        if (confirmed && form) {
            form.submit();
        }
    });
    return false;
};

// Make adminModal globally available
window.adminModal = adminModal;

// Design System Modal/Drawer helpers
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.removeAttribute('hidden');
        el.classList.add('is-open');
    }
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.setAttribute('hidden', 'true');
        el.classList.remove('is-open');
    }
};

window.openDrawer = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.removeAttribute('hidden');
        el.classList.add('is-open');
    }
};

window.closeDrawer = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.setAttribute('hidden', 'true');
        el.classList.remove('is-open');
    }
};

// Global Lead Details Drawer loading logic - Redirects directly to redesigned workspace
window.openLeadDrawer = function(event, leadId) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    window.location.href = '/admin/leads/' + leadId;
};

window.closeLeadDrawer = function() {
    const overlay = document.getElementById('lead-drawer');
    if (overlay) {
        overlay.classList.remove('is-open');
        overlay.setAttribute('hidden', 'true');
    }
};

