document.addEventListener('DOMContentLoaded', () => {

    // ========================================================
    // 1. MODO OSCURO / MODO CLARO COMPARTIDO
    // ========================================================
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeText = document.getElementById('themeText');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    const moonSvg = `<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>`;
    const sunSvg = `
        <circle cx="12" cy="12" r="5"></circle>
        <line x1="12" y1="1" x2="12" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="23"></line>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
        <line x1="1" y1="12" x2="3" y2="12"></line>
        <line x1="21" y1="21" x2="23" y2="12"></line>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
    `;

    function applyTheme(theme) {
        if (theme === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
            if (themeText) themeText.textContent = 'Modo claro';
            if (themeIcon) themeIcon.innerHTML = sunSvg;
        } else {
            htmlElement.removeAttribute('data-theme');
            if (themeText) themeText.textContent = 'Modo oscuro';
            if (themeIcon) themeIcon.innerHTML = moonSvg;
        }
        localStorage.setItem('theme', theme);
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const isDark = htmlElement.getAttribute('data-theme') === 'dark';
            applyTheme(isDark ? 'light' : 'dark');
        });
    }

    // ========================================================
    // 2. BUSCADOR EN TIEMPO REAL PARA CUALQUIER TABLA
    // ========================================================
    const searchInput = document.getElementById('searchPatients');
    const tableBody = document.querySelector('#patientsTable tbody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // ========================================================
    // 3. ACCIONES EN TABLAS (VER Y ELIMINAR)
    // ========================================================
    if (tableBody) {
        tableBody.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const row = btn.closest('tr');
            if (!row) return;

            // Botón VER (para tablas genéricas sin modal interactivo propio)
           if (
    btn.classList.contains('btn-tbl-view') &&
    !btn.classList.contains('btn-view-user') &&
    !btn.classList.contains('btn-view-patient') &&
    !btn.classList.contains('btn-view-atn')
) {
                const cells = Array.from(row.querySelectorAll('td'));
                const headers = Array.from(document.querySelectorAll('#patientsTable thead th'));
                let info = "📋 Información del Registro:\n\n";
                headers.forEach((h, i) => {
                    if (h.textContent.trim().toLowerCase() !== 'acciones' && cells[i]) {
                        info += `• ${h.textContent.trim()}: ${cells[i].textContent.trim()}\n`;
                    }
                });
                alert(info);
            }

            // Botón ELIMINAR
            if (btn.classList.contains('btn-tbl-delete')) {
                const identificador = row.children[1] ? row.children[1].textContent.trim() : 'este registro';
                const confirmacion = confirm(`¿Estás seguro de eliminar el registro de "${identificador}"?`);
                if (confirmacion) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    row.style.transition = 'all 0.25s ease';
                    setTimeout(() => row.remove(), 250);
                }
            }
        });
    }

});
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('sidebar-open');
        });

        // Cerrar el menú si se hace clic fuera de él en dispositivos móviles
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('sidebar-open');
                }
            }
        });
    }
});