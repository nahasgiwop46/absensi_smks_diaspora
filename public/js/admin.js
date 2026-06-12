/**
 * Admin Panel - Absensi QR
 */

// Filter tabel (digunakan di halaman siswa, user, dll)
function filterTable(tableId, searchId, kelasId, statusId) {
    const search = document.getElementById(searchId)?.value?.toLowerCase() || '';
    const kelas  = document.getElementById(kelasId)?.value || '';
    const status = document.getElementById(statusId)?.value || '';
    
    const rows = document.querySelectorAll(`#${tableId} tbody tr`);
    rows.forEach(row => {
        const text      = row.textContent.toLowerCase();
        const rowKelas  = row.cells[3]?.textContent?.trim() || '';
        const rowStatus = row.cells[6]?.textContent?.trim() || '';
        
        const matchSearch = text.includes(search);
        const matchKelas  = !kelas || rowKelas.includes(kelas);
        const matchStatus = !status || rowStatus === status;
        
        row.style.display = (matchSearch && matchKelas && matchStatus) ? '' : 'none';
    });
}

// Konfirmasi hapus
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin?');
}

// Toggle sidebar mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar?.classList.toggle('mobile-open');
}