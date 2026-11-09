<?php
// Parámetro para saber qué pestaña resaltar: 'inicio', 'paciente', 'atencion', 'seguimiento', 'usuarios'
$paginaActiva = $paginaActiva ?? 'inicio';
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
?>
<!-- Barra Lateral Reutilizable -->
<aside class="sidebar">

    <!-- Tarjeta Blanca del Logotipo ISSSTE -->
    <div class="sidebar-logo-card">
        <img src="logo-issste.png" alt="Logotipo ISSSTE" class="sidebar-logo-img">
    </div>

    <!-- Navegación de Pestañas con Píldora Translúcida Activa -->
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link <?= ($paginaActiva === 'inicio') ? 'active' : '' ?>">
            INICIO
        </a>
        <a href="tablas_pacientes.php" class="nav-link <?= ($paginaActiva === 'paciente') ? 'active' : '' ?>">
            PACIENTE
        </a>
        <a href="tablas_atencion.php" class="nav-link <?= ($paginaActiva === 'atencion') ? 'active' : '' ?>">
            ATENCIÓN
        </a>
        <a href="tablas_seguimiento.php" class="nav-link <?= ($paginaActiva === 'seguimiento') ? 'active' : '' ?>">
            SEGUIMIENTO
        </a>

        <!-- Módulo de Usuarios visible SOLO para Admin y Director General -->
        <?php if ($rolUsuario === 'admin' || $rolUsuario === 'director'): ?>
            <a href="tablas_usuarios.php" class="nav-link <?= ($paginaActiva === 'usuarios') ? 'active' : '' ?>">
                USUARIOS
            </a>
        <?php endif; ?>
    </nav>

    <!-- Cerrar sesión -->
    <div class="sidebar-footer">
        <a href="index.php" class="logout-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Cerrar Sesión</span>
        </a>
    </div>

</aside>