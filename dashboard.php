<?php
session_start();
require_once "conexion.php";

// Seguridad: Si no ha iniciado sesión, redirigir al login
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$nombreUsuario = $_SESSION["usuario"];
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
$paginaActiva = 'inicio';
$tituloHeader = 'Panel General de Monitoreo';

// Contadores automáticos desde la base de datos
$countPacientes = 142;
$countAtenciones = 98;
$countSeguimientos = 11;
$countVisitas = 65;

if (!empty($conectar)) {
    $qPac = @mysqli_query($conectar, "SELECT COUNT(*) as total FROM pacientes");
    if ($qPac && $r = mysqli_fetch_assoc($qPac)) $countPacientes = $r['total'];

    $qAtn = @mysqli_query($conectar, "SELECT COUNT(*) as total FROM atencion");
    if ($qAtn && $r = mysqli_fetch_assoc($qAtn)) $countAtenciones = $r['total'];

    $qSeg = @mysqli_query($conectar, "SELECT COUNT(*) as total FROM seguimiento");
    if ($qSeg && $r = mysqli_fetch_assoc($qSeg)) $countSeguimientos = $r['total'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Hospitalario ISSSTE</title>
    <!-- Fuentes Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <!-- Script Anti-Parpadeo Inmediato de Modo Oscuro -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>

<body>

    <div class="dashboard-container">
        <!-- Barra Lateral -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido Exclusivo del Dashboard (INICIO) -->
            <section class="content-body">
                
                <!-- Saludo personalizado con el rol -->
                <div class="welcome-banner" style="margin-bottom: 25px;">
                    <h2>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>
                    <p style="opacity: 0.8; font-size: 14px;">Perfil del sistema: <strong><?= ucfirst(htmlspecialchars($rolUsuario)) ?></strong></p>
                </div>

                <!-- Tarjetas KPI Principales -->
                <div class="kpi-grid">
                    <!-- Tarjeta 1: Pacientes Ingresados -->
                    <div class="kpi-card">
                        <h3 class="kpi-title">PACIENTES INGRESADOS</h3>
                        <div class="kpi-number">
                            <span class="hash">#</span> <?= $countPacientes ?>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Pacientes Egresados -->
                    <div class="kpi-card">
                        <h3 class="kpi-title">PACIENTES EGRESADOS</h3>
                        <div class="kpi-number">
                            <span class="hash">#</span> <?= $countAtenciones ?>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Pacientes Fallecidos -->
                    <div class="kpi-card">
                        <h3 class="kpi-title">PACIENTES FALLECIDOS</h3>
                        <div class="kpi-number">
                            <span class="hash">#</span> <?= $countSeguimientos ?>
                        </div>
                    </div>

                    <!-- Tarjeta 4: Visitas -->
                    <div class="kpi-card">
                        <h3 class="kpi-title">VISITAS</h3>
                        <div class="kpi-number">
                            <span class="hash">#</span> <?= $countVisitas ?>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN DINÁMICA SEGÚN EL ROL -->
                <div class="role-specific-section" style="margin-top: 35px;">
                    
                    <!-- Accesos exclusivos para Administradores y Directores -->
                    <?php if ($rolUsuario === 'admin' || $rolUsuario === 'director'): ?>
                        <div class="admin-control-panel" style="background: var(--card-bg, #fff); padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px;">
                            <h3 style="margin-bottom: 10px; font-size: 18px;">Panel de Administración de Personal</h3>
                            <p style="margin-bottom: 15px; font-size: 14px; opacity: 0.8;">Tienes privilegios para gestionar las cuentas de usuario y credenciales del hospital.</p>
                            <a href="tablas_usuarios.php" class="btn-action" style="display: inline-block; padding: 10px 20px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600;">Gestionar Usuarios</a>
                        </div>
                    <?php endif; ?>

                    <!-- Panel exclusivo para el Director General -->
                    <?php if ($rolUsuario === 'director'): ?>
                        <div class="director-control-panel" style="background: var(--card-bg, #fff); padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                            <h3 style="margin-bottom: 10px; font-size: 18px;">Supervisión Directiva</h3>
                            <p style="margin-bottom: 15px; font-size: 14px; opacity: 0.8;">Acceso a reportes globales y métricas consolidadas del Hospital Regional del ISSSTE.</p>
                            <a href="#" class="btn-action" style="display: inline-block; padding: 10px 20px; background: #059669; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600;">Ver Reportes Globales</a>
                        </div>
                    <?php endif; ?>

                </div>

            </section>
        </main>
    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
</body>

</html>