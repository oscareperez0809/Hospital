<?php
$tituloHeader = $tituloHeader ?? 'Panel General de Monitoreo';
$subtituloHeader = $subtituloHeader ?? 'ISSSTE • Residencia Profesional';
$usuarioActual = $_SESSION['usuario'] ?? 'Personal Médico';
?>
<!-- Cabecera Superior Reutilizable -->
<header class="top-header">
    <div class="header-title-container">
        <h2><?= htmlspecialchars($tituloHeader) ?></h2>
        <p><?= htmlspecialchars($subtituloHeader) ?></p>
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
