<?php
session_start();
require_once "conexion.php";
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

                    <!-- Tarjeta 4: ¿Visitas? -->
                    <div class="kpi-card">
                        <h3 class="kpi-title">¿VISITAS?</h3>
                        <div class="kpi-number">
                            <span class="hash">#</span> <?= $countVisitas ?>
                        </div>
                    </div>

                </div>
            </section>
        </main>

    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
</body>

</html>