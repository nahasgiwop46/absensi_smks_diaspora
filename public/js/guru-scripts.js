let sidebarCollapsed = false;
let qrInterval = null, qrSeconds = 0;

function toggleDesktopSidebar() {
    sidebarCollapsed = !sidebarCollapsed;
    document.getElementById('sidebarDesktop').classList.toggle('collapsed', sidebarCollapsed);
    document.getElementById('mainContent').classList.toggle('expanded', sidebarCollapsed);
    localStorage.setItem('guruSidebarCollapsed', sidebarCollapsed);
}

document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('guruSidebarCollapsed') === 'true') {
        toggleDesktopSidebar();
    }
});

function openMobileSidebar() {
    document.getElementById('sidebarMobile').classList.add('show');
    document.getElementById('sidebarOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
    document.getElementById('sidebarMobile').classList.remove('show');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

function generateQR() {
    qrSeconds = 600;
    document.getElementById('qrArea').classList.add('generated');
    document.getElementById('qrText').textContent = 'QR Aktif!';
    document.getElementById('qrDetail').style.display = 'block';
    document.getElementById('btnGenerate').classList.add('d-none');
    document.getElementById('btnStop').classList.remove('d-none');
    updateTimer();
    qrInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
    const m = Math.floor(qrSeconds/60), s = qrSeconds%60;
    document.getElementById('qrTimer').textContent = String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
    if (qrSeconds <= 60) document.getElementById('qrTimer').style.color = '#ef4444';
    if (qrSeconds <= 0) { stopQR(); showToast('QR expired!', 'warning'); }
    qrSeconds--;
}

function stopQR() {
    clearInterval(qrInterval); 
    qrInterval = null;
}

function showToast(m, type = 'success') {
    const c = document.getElementById('toastContainer');
    if (!c) return;
    const bc = type==='success'?'#10b981':type==='danger'?'#ef4444':type==='warning'?'#f59e0b':'#3b82f6';
    c.insertAdjacentHTML('beforeend',`<div class="toast align-items-center border-0 shadow-lg w-100" style="background:white;border-left:4px solid ${bc};border-radius:10px;margin-bottom:8px;"><div class="d-flex"><div class="toast-body d-flex align-items-center gap-2 small"><span>${m}</span></div><button class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`);
    const te = c.lastElementChild; 
    new bootstrap.Toast(te,{delay:2500}).show(); 
    te.addEventListener('hidden.bs.toast',()=>te.remove());
}