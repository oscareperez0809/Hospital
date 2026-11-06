<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'atencion';
$tituloHeader = 'Control de Atención Médica';

$mensajeExito = '';
$mensajeError = '';

// 1. Procesar Eliminación (?eliminar_id=...)
if (isset($_GET['eliminar_id']) && !empty($conectar)) {
    $idDel = intval($_GET['eliminar_id']);
    $stmt = mysqli_prepare($conectar, "DELETE FROM atencion WHERE id_atencion = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $idDel);
        if (mysqli_stmt_execute($stmt)) {
            $mensajeExito = "Atención médica eliminada correctamente (los seguimientos asociados se eliminaron en cascada).";
        } else {
            $mensajeError = "Error al eliminar: " . mysqli_error($conectar);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención Médica - ISSSTE</title>
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

        <!-- Barra Lateral con ATENCIÓN activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">

            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Atención -->
            <section class="content-body">

                <!-- Alertas de éxito o error -->
                <?php if (!empty($mensajeExito)): ?>
                    <div style="background-color: rgba(34, 197, 94, 0.15); border: 1.5px solid #22c55e; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <span>✓</span> <?= htmlspecialchars($mensajeExito) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                    <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <span>⚠</span> <?= htmlspecialchars($mensajeError) ?>
                    </div>
                <?php endif; ?>

                <!-- Barra superior con REGISTRAR, filtros y BUSCAR -->
                <div class="table-toolbar-container">
                    <div class="table-toolbar-left">
                        <a href="formulario_atencion.php" class="btn-chip" style="font-weight: 700;">
                            REGISTRAR
                        </a>
                        <button type="button" class="btn-chip btn-filter-atn active" data-filter="todos">Todos</button>
                    </div>

                    <!-- Cápsula BUSCAR a la derecha -->
                    <div class="search-pill-container">
                        <input
                            type="text"
                            id="searchPatients"
                            class="search-pill-input"
                            placeholder="BUSCAR"
                            autocomplete="off">
                        <span class="search-pill-icon" title="Buscar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Atención Médica -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>ID Atención</th>
                                <th>Cédula</th>
                                <th>Paciente</th>
                                <th>Departamento</th>
                                <th>Especialidad</th>
                                <th>Etiología</th>
                                <th>Cama</th>
                                <th>Piso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hayAtencionesBD = false;

                            if (!empty($conectar)) {
                                $sqlAtn = "SELECT a.*, 
                                                p.nombre AS pac_nombre, p.apellido_paterno AS pac_paterno, p.apellido_materno AS pac_materno,
                                                d.departamento, 
                                                e.especialidad, 
                                                et.etiologia
                                           FROM atencion a
                                           LEFT JOIN pacientes p ON a.cedula = p.cedula
                                           LEFT JOIN departamentos d ON a.id_departamento = d.id_departamento
                                           LEFT JOIN especialidades e ON a.id_especialidad = e.id_especialidad
                                           LEFT JOIN etiologia et ON a.id_etiologia = et.id_etiologia
                                           ORDER BY a.id_atencion DESC";

                                $consultaAtn = @mysqli_query($conectar, $sqlAtn);

                                if ($consultaAtn && mysqli_num_rows($consultaAtn) > 0) {
                                    $hayAtencionesBD = true;
                                    while ($a = mysqli_fetch_assoc($consultaAtn)) {
                                        $idAtnFormato = "ATN-" . str_pad($a['id_atencion'], 3, '0', STR_PAD_LEFT);
                                        $ced = htmlspecialchars($a['cedula']);
                                        $nomPac = trim(($a['pac_nombre'] ?? '') . ' ' . ($a['pac_paterno'] ?? '') . ' ' . ($a['pac_materno'] ?? ''));
                                        if (empty($nomPac)) $nomPac = "Paciente Céd. $ced";
                                        $dep = htmlspecialchars($a['departamento'] ?? 'No asignado');
                                        $esp = htmlspecialchars($a['especialidad'] ?? 'General');
                                        $eti = htmlspecialchars($a['etiologia'] ?? 'No especificada');
                                        $cam = !empty($a['cama']) ? htmlspecialchars($a['cama']) : '--';
                                        $pis = !empty($a['piso']) ? htmlspecialchars($a['piso']) : '--';
                            ?>
                                        <tr>
                                            <td class="cell-cedula"><?= $idAtnFormato ?></td>
                                            <td><?= $ced ?></td>
                                            <td><strong><?= htmlspecialchars($nomPac) ?></strong></td>
                                            <td><?= $dep ?></td>
                                            <td><?= $esp ?></td>
                                            <td><?= $eti ?></td>
                                            <td><?= $cam ?></td>
                                            <td><?= $pis ?></td>
                                            <td class="cell-actions">
                                                <!-- Botón Ver: Enruta directamente a ver_atencion.php -->
                                                <a href="ver_atencion.php?id_atencion=<?= urlencode($a['id_atencion']) ?>" class="btn-tbl btn-tbl-view" style="text-decoration:none;" title="Ver detalle de atención">Ver</a>

                                                <!-- Botón Editar: Enruta a modificar_atencion.php -->
                                                <a href="modificar_atencion.php?id_atencion=<?= urlencode($a['id_atencion']) ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar atención">Editar</a>

                                                <!-- Botón Eliminar -->
                                                <a href="tablas_atencion.php?eliminar_id=<?= urlencode($a['id_atencion']) ?>" class="btn-tbl btn-tbl-delete" onclick="return confirm('¿Seguro que deseas eliminar la atención <?= $idAtnFormato ?> del paciente <?= htmlspecialchars($nomPac) ?>?');" style="text-decoration:none; display:inline-flex; align-items:center;" title="Eliminar atención">Eliminar</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                            }

                            if (!$hayAtencionesBD):
                                ?>
                                <tr>
                                    <td colspan="9" style="text-align:center; padding: 48px 20px; color: var(--text-secondary);">
                                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--text-primary);">No hay atenciones médicas registradas</div>
                                        <p style="font-size: 13.5px;">Haga clic en el botón <strong>REGISTRAR</strong> para crear la primera atención médica.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>

    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
</body>

</html>