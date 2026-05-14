/**
 * Admin Form Handler
 * Handles AJAX form submissions with notifications
 */

class AdminFormHandler {
    constructor() {
        this.isSubmitting = false;
        this.init();
    }

    init() {
        // Wait for notification system to be ready
        document.addEventListener('DOMContentLoaded', () => {
            this.setupFormHandlers();
        });
        
        // Also try to set up immediately in case DOMContentLoaded already fired
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            this.setupFormHandlers();
        }
    }

    setupFormHandlers() {
        // Handle blog post forms
        const blogForm = document.getElementById('blogForm');
        if (blogForm) {
            this.setupBlogFormHandler(blogForm);
        }

        // Handle other forms with data-ajax-form attribute
        document.querySelectorAll('form[data-ajax-form]').forEach(form => {
            this.setupFormHandler(form);
        });
    }

    setupBlogFormHandler(form) {
        form.addEventListener('submit', (e) => this.handleBlogFormSubmit(e, form));
    }

    setupFormHandler(form) {
        form.addEventListener('submit', (e) => this.handleFormSubmit(e, form));
    }

    async handleBlogFormSubmit(e, form) {
        e.preventDefault();

        if (this.isSubmitting) {
            notificationSystem?.warning('Wait', 'A request is already in progress.');
            return;
        }

        this.isSubmitting = true;
        const submitButtons = form.querySelectorAll('button[type="submit"]');
        const originalTexts = Array.from(submitButtons).map(btn => btn.textContent);
        const statusAction = document.activeElement?.name === 'status_action' 
            ? document.activeElement.value 
            : 'draft';

        // Disable all submit buttons and show loading state
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.textContent = 'Saving...';
        });

        try {
            // Get form data
            const formData = new FormData(form);

            // Submit via fetch
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            // Handle validation errors (422)
            if (response.status === 422) {
                const data = await response.json();
                const firstError = Object.values(data.errors || {})[0];
                const errorMessage = Array.isArray(firstError) ? firstError[0] : firstError || 'Validation failed. Please check your input.';
                notificationSystem?.error('Validation Error', errorMessage, 7000);
            } else if (response.ok) {
                const data = await response.json();
                
                // Success
                const action = statusAction === 'publish' ? 'Published' : 'Saved';
                notificationSystem?.success(
                    'Success!',
                    `Blog post ${action.toLowerCase()} successfully.`,
                    5000
                );

                // Redirect after short delay if redirect URL provided
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                // Other server errors
                const data = await response.json().catch(() => ({}));
                const errorMessage = data.message || 'An error occurred while saving.';
                notificationSystem?.error('Error', errorMessage, 7000);
            }
        } catch (error) {
            console.error('Form submission error:', error);
            notificationSystem?.error(
                'Failed to save',
                error.message || 'An unexpected error occurred. Please try again.',
                7000
            );
        } finally {
            // Re-enable submit buttons and restore text
            submitButtons.forEach((btn, index) => {
                btn.disabled = false;
                btn.textContent = originalTexts[index];
            });

            this.isSubmitting = false;
        }
    }

    async handleFormSubmit(e, form) {
        e.preventDefault();

        if (this.isSubmitting) {
            notificationSystem?.warning('Wait', 'A request is already in progress.');
            return;
        }

        this.isSubmitting = true;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton?.textContent || 'Submit';

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
        }

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            // Handle validation errors (422)
            if (response.status === 422) {
                const data = await response.json();
                const firstError = Object.values(data.errors || {})[0];
                const errorMessage = Array.isArray(firstError) ? firstError[0] : firstError || 'Validation failed. Please check your input.';
                notificationSystem?.error('Validation Error', errorMessage, 7000);
            } else if (response.ok) {
                const data = await response.json();
                notificationSystem?.success(
                    'Success!',
                    data.message || 'Form submitted successfully.',
                    5000
                );

                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                const data = await response.json().catch(() => ({}));
                notificationSystem?.error(
                    'Error',
                    data.message || 'An error occurred.',
                    7000
                );
            }
        } catch (error) {
            console.error('Form submission error:', error);
            notificationSystem?.error(
                'Failed to submit',
                error.message || 'An unexpected error occurred.',
                7000
            );
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }

            this.isSubmitting = false;
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new AdminFormHandler();
    });
} else {
    new AdminFormHandler();
}
