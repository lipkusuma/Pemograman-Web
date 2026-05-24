/**
 * App.js - Global JavaScript
 * Fitur: Dark Mode Toggle + Sidebar Mobile Toggle
 */

// ===== DARK MODE =====
function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark');

    const isDark = body.classList.contains('dark');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');

    updateDarkModeIcon(isDark);
}

function updateDarkModeIcon(isDark) {
    const moonIcons = document.querySelectorAll('.icon-moon');
    const sunIcons = document.querySelectorAll('.icon-sun');
    const label = document.querySelector('.dark-mode-label');

    moonIcons.forEach(icon => icon.style.display = isDark ? 'none' : 'block');
    sunIcons.forEach(icon => icon.style.display = isDark ? 'block' : 'none');
    if (label) label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
}

// Saat halaman load, cek preferensi dark mode dari localStorage
function initDarkMode() {
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'enabled') {
        document.body.classList.add('dark');
        updateDarkModeIcon(true);
    }
}

// ===== SIDEBAR MOBILE TOGGLE =====
function initSidebarToggle() {
    const btn = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (btn && sidebar) {
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Tutup sidebar saat klik di luar sidebar (mobile)
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('show') && 
                !sidebar.contains(e.target) && 
                !btn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    initSidebarToggle();
});
