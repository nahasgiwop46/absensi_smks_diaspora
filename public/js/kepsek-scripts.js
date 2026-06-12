let sidebarCollapsed=!1;
function toggleDesktopSidebar(){sidebarCollapsed=!sidebarCollapsed;document.getElementById('sidebarDesktop').classList.toggle('collapsed',sidebarCollapsed);document.getElementById('mainContent').classList.toggle('expanded',sidebarCollapsed);}
function openMobileSidebar(){document.getElementById('sidebarMobile').classList.add('show');document.getElementById('sidebarOverlay').classList.add('show');document.body.style.overflow='hidden';}
function closeMobileSidebar(){document.getElementById('sidebarMobile').classList.remove('show');document.getElementById('sidebarOverlay').classList.remove('show');document.body.style.overflow='';}
function showToast(m,type='success'){
    const c=document.getElementById('toastContainer');
    const bc=type==='success'?'#10b981':type==='danger'?'#ef4444':type==='warning'?'#f59e0b':'#8b5cf6';
    c.insertAdjacentHTML('beforeend',`<div class="toast align-items-center border-0 shadow-lg w-100" style="background:white;border-left:4px solid ${bc};border-radius:10px;margin-bottom:8px;"><div class="d-flex"><div class="toast-body d-flex align-items-center gap-2 small"><span>${m}</span></div><button class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`);
    const te=c.lastElementChild;new bootstrap.Toast(te,{delay:2500}).show();te.addEventListener('hidden.bs.toast',()=>te.remove());
}