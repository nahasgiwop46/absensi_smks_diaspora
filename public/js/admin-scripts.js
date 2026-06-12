/**
 * ADMIN DASHBOARD SCRIPTS - SMKS Diaspora
 */

let sidebarCollapsed = false;
const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '';

// ========== SIDEBAR TOGGLE ==========
function toggleDesktopSidebar() {
    sidebarCollapsed = !sidebarCollapsed;
    const sidebar = document.getElementById('sidebarDesktop');
    const mainContent = document.getElementById('mainContent');
    
    if (sidebar) sidebar.classList.toggle('collapsed', sidebarCollapsed);
    if (mainContent) mainContent.classList.toggle('expanded', sidebarCollapsed);
    
    // Simpan state ke localStorage
    localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
}

// Restore sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebarCollapsed = true;
        const sidebar = document.getElementById('sidebarDesktop');
        const mainContent = document.getElementById('mainContent');
        if (sidebar) sidebar.classList.add('collapsed');
        if (mainContent) mainContent.classList.add('expanded');
    }
});

// ========== MOBILE SIDEBAR ==========
function openMobileSidebar() {
    const sidebar = document.getElementById('sidebarMobile');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) sidebar.classList.add('show');
    if (overlay) overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebarMobile');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) sidebar.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
    document.body.style.overflow = '';
}

// Tutup sidebar saat klik overlay
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }
});

// ========== TOAST NOTIFICATION ==========
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const borderColors = {
        'success': '#10b981',
        'danger': '#ef4444',
        'warning': '#f59e0b',
        'info': '#3b82f6'
    };
    
    const borderColor = borderColors[type] || borderColors.info;
    const iconMap = {
        'success': 'bi-check-circle-fill',
        'danger': 'bi-exclamation-triangle-fill',
        'warning': 'bi-exclamation-circle-fill',
        'info': 'bi-info-circle-fill'
    };
    const icon = iconMap[type] || iconMap.info;
    
    const toastHTML = `
        <div class="toast align-items-center border-0 shadow-lg" 
             style="background:white; border-left:4px solid ${borderColor}; border-radius:10px; margin-bottom:8px;"
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2 small">
                    <i class="bi ${icon} text-${type}"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = container.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
}

// ========== KONFIRMASI DELETE ==========
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// ========== HANDLE LOGOUT ==========
function handleLogout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = BASE_URL + '/logout';
    }
}

// ========== AUTO-HIDE ALERT ==========
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide flash messages after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        }, 5000);
    });
});

// ========== EXPORT ALERT (DEMO) ==========
function exportData(format) {
    showToast(`Mohon maaf, fitur export ${format.toUpperCase()} sedang dalam pengembangan.`, 'info');
}