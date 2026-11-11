<?php
$tituloHeader = $tituloHeader ?? 'Panel General de Monitoreo';
$subtituloHeader = $subtituloHeader ?? 'ISSSTE • Residencia Profesional';
$usuarioActual = $_SESSION['usuario'] ?? 'Personal Médico';
?>
<!-- Cabecera Superior Reutilizable -->
<header class="top-header">
    <div class="header-title-container" style="display: flex; align-items: center; gap: 12px;">
        <!-- Botón de Menú Hamburguesa (Visible solo en responsive gracias al CSS) -->
        <button type="button" class="hamburger-toggle" id="sidebarToggle" aria-label="Abrir menú">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <div>
            <h2><?= htmlspecialchars($tituloHeader) ?></h2>
            <p><?= htmlspecialchars($subtituloHeader) ?></p>
        </div>
    </div>

    <div class="header-actions">
        <!-- Botón de Modo Oscuro -->
        <button id="themeToggle" class="btn-theme-header" type="button" aria-label="Cambiar tema">
            <svg id="themeIcon" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            <span id="themeText">Modo oscuro</span>
        </button>

        <!-- Insignia del usuario -->
        <div class="user-badge">
            <div class="user-avatar">
                <?= strtoupper(substr($usuarioActual, 0, 1)) ?>
            </div>
            <span><?= htmlspecialchars($usuarioActual) ?></span>
        </div>
    </div>
</header>