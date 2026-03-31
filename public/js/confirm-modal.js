// Confirmation Modal System
class ConfirmModal {
    constructor() {
        this.initializeModals();
    }

    initializeModals() {
        // Delete Confirmation
        document.querySelectorAll('[data-confirm-delete]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const message = button.getAttribute('data-message') || 
                              'Data yang dihapus tidak dapat dikembalikan.';
                const formId = button.getAttribute('data-form-id');
                
                this.showDeleteModal(message, formId);
            });
        });

        // Action Confirmation
        document.querySelectorAll('[data-confirm-action]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const title = button.getAttribute('data-title') || 'Konfirmasi';
                const message = button.getAttribute('data-message') || 
                              'Apakah Anda yakin ingin melanjutkan?';
                const action = button.getAttribute('data-action');
                const method = button.getAttribute('data-method') || 'POST';
                
                this.showActionModal(title, message, action, method);
            });
        });

        // Logout Confirmation
        document.querySelectorAll('[data-confirm-logout]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.showLogoutModal();
            });
        });
    }

    showDeleteModal(message, formId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const modalMessage = document.getElementById('deleteModalMessage');
        const deleteForm = document.getElementById('deleteForm');
        
        modalMessage.textContent = message;
        
        if (formId) {
            const targetForm = document.getElementById(formId);
            if (targetForm) {
                deleteForm.action = targetForm.action;
                deleteForm.method = targetForm.method;
                
                // Copy CSRF token
                const csrfToken = targetForm.querySelector('input[name="_token"]');
                if (csrfToken) {
                    deleteForm.querySelector('input[name="_token"]').value = csrfToken.value;
                }
            }
        }
        
        modal.show();
    }

    showActionModal(title, message, action, method) {
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const modalTitle = document.getElementById('confirmModalTitle');
        const modalMessage = document.getElementById('confirmModalMessage');
        const confirmButton = document.getElementById('confirmModalButton');
        
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        
        // Set action untuk button
        confirmButton.onclick = () => {
            this.submitAction(action, method);
            modal.hide();
        };
        
        modal.show();
    }

    showLogoutModal() {
        const modal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));
        modal.show();
    }

    submitAction(action, method) {
        const form = document.createElement('form');
        form.method = method;
        form.action = action;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.confirmModal = new ConfirmModal();
});