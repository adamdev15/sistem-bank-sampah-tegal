{{-- Modal Konfirmasi Bank Sampah --}}
<div class="bank-modal" id="bankConfirmModal">
    <div class="bank-modal-content">
        <div class="bank-modal-header">
            <h3 class="bank-modal-title">Konfirmasi</h3>
            <button type="button" class="bank-modal-close">&times;</button>
        </div>
        <div class="bank-modal-body">
            <p id="bankConfirmMessage">Apakah Anda yakin ingin melanjutkan?</p>
        </div>
        <div class="bank-modal-footer">
            <button type="button" class="bank-btn-secondary bank-modal-cancel">Batal</button>
            <button type="button" class="bank-btn-primary" id="bankConfirmButton">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="bank-modal" id="bankDeleteModal">
    <div class="bank-modal-content">
        <div class="bank-modal-header bank-modal-header-danger">
            <h3 class="bank-modal-title"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h3>
            <button type="button" class="bank-modal-close">&times;</button>
        </div>
        <div class="bank-modal-body">
            <div class="bank-alert bank-alert-danger">
                <h4><i class="fas fa-exclamation-circle"></i> PERHATIAN!</h4>
                <p id="bankDeleteMessage">Data yang dihapus tidak dapat dikembalikan.</p>
                <p class="bank-mb-0">Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
        </div>
        <div class="bank-modal-footer">
            <button type="button" class="bank-btn-secondary bank-modal-cancel">Batalkan</button>
            <form id="bankDeleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="bank-btn-danger">
                    <i class="fas fa-trash"></i> Ya, Hapus Data
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Logout --}}
<div class="bank-modal" id="bankLogoutModal">
    <div class="bank-modal-content">
        <div class="bank-modal-header">
            <h3 class="bank-modal-title"><i class="fas fa-sign-out-alt"></i> Konfirmasi Logout</h3>
            <button type="button" class="bank-modal-close">&times;</button>
        </div>
        <div class="bank-modal-body">
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div class="bank-modal-footer">
            <button type="button" class="bank-btn-secondary bank-modal-cancel">Batal</button>
            <form id="bankLogoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="bank-btn-primary">
                    <i class="fas fa-sign-out-alt"></i> Ya, Logout
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Styles untuk Bank Sampah */
.bank-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.bank-modal.show {
    display: flex;
}

.bank-modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: bankModalSlideIn 0.3s ease;
}

@keyframes bankModalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.bank-modal-header {
    background: #2c3e50;
    color: white;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bank-modal-header-danger {
    background: #e74c3c;
}

.bank-modal-title {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.bank-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bank-modal-body {
    padding: 20px;
}

.bank-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.bank-btn-secondary {
    background: #95a5a6;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.bank-btn-secondary:hover {
    background: #7f8c8d;
}

.bank-btn-primary {
    background: #3498db;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.bank-btn-primary:hover {
    background: #2980b9;
}

.bank-btn-danger {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.bank-btn-danger:hover {
    background: #c0392b;
}
</style>

<script>
// Modal JavaScript untuk Bank Sampah
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const logoutBtn = document.querySelector('[data-bank-confirm-logout]');
    const confirmModal = document.getElementById('bankConfirmModal');
    const deleteModal = document.getElementById('bankDeleteModal');
    const logoutModal = document.getElementById('bankLogoutModal');
    
    // Logout Button
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            bankShowModal('logout');
        });
    }
    
    // Delete Confirm Buttons
    document.querySelectorAll('[data-bank-confirm-delete]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-message') || 
                          'Data yang dihapus tidak dapat dikembalikan.';
            const formId = this.getAttribute('data-form-id');
            
            if (formId) {
                const form = document.getElementById(formId);
                if (form) {
                    document.getElementById('bankDeleteForm').action = form.action;
                }
            }
            
            document.getElementById('bankDeleteMessage').textContent = message;
            bankShowModal('delete');
        });
    });
    
    // Close buttons
    document.querySelectorAll('.bank-modal-close, .bank-modal-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            bankHideAllModals();
        });
    });
    
    // Close modal when clicking outside
    document.querySelectorAll('.bank-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                bankHideAllModals();
            }
        });
    });
    
    // Functions
    function bankShowModal(type) {
        bankHideAllModals();
        
        switch(type) {
            case 'confirm':
                confirmModal.classList.add('show');
                break;
            case 'delete':
                deleteModal.classList.add('show');
                break;
            case 'logout':
                logoutModal.classList.add('show');
                break;
        }
    }
    
    function bankHideAllModals() {
        document.querySelectorAll('.bank-modal').forEach(modal => {
            modal.classList.remove('show');
        });
    }
});
</script>