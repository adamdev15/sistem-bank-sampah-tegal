// Delete Confirmation System
class DeleteConfirmation {
    constructor() {
        this.deleteForm = null;
        this.modal = null;
        this.initialize();
    }

    initialize() {
        // Initialize modal
        this.modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        
        // Setup delete buttons
        document.querySelectorAll('[data-confirm-delete]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.showConfirmation(button);
            });
        });

        // Setup confirm button
        document.getElementById('confirmDeleteButton').addEventListener('click', () => {
            this.confirmDelete();
        });

        // Handle success/error messages from server
        this.handleServerMessages();
    }

    showConfirmation(button) {
        const message = button.getAttribute('data-message') || 
                       'Apakah Anda yakin ingin menghapus data ini?';
        const formId = button.getAttribute('data-form-id');
        
        // Set message
        document.getElementById('deleteModalMessage').textContent = message;
        
        // Get form
        this.deleteForm = document.getElementById(formId);
        
        // Show modal
        this.modal.show();
    }

    confirmDelete() {
        if (this.deleteForm) {
            // Show loading state
            const confirmBtn = document.getElementById('confirmDeleteButton');
            const originalHtml = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
            confirmBtn.disabled = true;
            
            // Submit form
            this.deleteForm.submit();
            
            // Hide modal after short delay
            setTimeout(() => {
                this.modal.hide();
                confirmBtn.innerHTML = originalHtml;
                confirmBtn.disabled = false;
            }, 1500);
        }
    }

    handleServerMessages() {
        // Check for success message
        const successMessage = document.querySelector('.alert-success')?.textContent;
        if (successMessage && successMessage.includes('dihapus')) {
            this.showSuccessModal(successMessage);
        }

        // Check for error message
        const errorMessage = document.querySelector('.alert-danger')?.textContent;
        if (errorMessage && errorMessage.includes('tidak dapat menghapus')) {
            this.showErrorModal(errorMessage);
        }
    }

    showSuccessModal(message) {
        document.getElementById('successModalMessage').textContent = message;
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }

    showErrorModal(message) {
        document.getElementById('errorModalMessage').textContent = message;
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.deleteConfirmation = new DeleteConfirmation();
});

// Handle form submissions with confirmation
document.addEventListener('submit', (e) => {
    if (e.target.matches('form[data-confirm]')) {
        e.preventDefault();
        const message = e.target.getAttribute('data-confirm-message') || 
                       'Apakah Anda yakin?';
        
        document.getElementById('deleteModalMessage').textContent = message;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const confirmBtn = document.getElementById('confirmDeleteButton');
        
        confirmBtn.onclick = () => {
            e.target.submit();
        };
        
        modal.show();
    }
});