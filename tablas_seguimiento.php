<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'seguimiento';
$tituloHeader = 'Control de Seguimiento Clínico';

// 1. Procesar Eliminación (?eliminar_id=...)
if (isset($_GET['eliminar_id']) && !empty($conectar)) {
    $idDel = intval($_GET['eliminar_id']);
    $stmt = mysqli_prepare($conectar, "DELETE FROM seguimiento WHERE id_seguimiento = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $idDel);
        mysqli_stmt_execute($stmt);
    }
    header("Location: tablas_seguimiento.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento Clínico - ISSSTE</title>
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

        <!-- Barra Lateral con SEGUIMIENTO activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">

            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Seguimiento -->
            <section class="content-body">

                <!-- Barra superior unificada con REGISTRAR y filtros por datos de paciente/ubicación -->
                <div class="table-toolbar-container">
                    <div class="table-toolbar-left">
                        <a href="formulario_seguimiento.php" class="btn-chip" style="font-weight: 700; text-decoration: none;">
                            REGISTRAR
                        </a>
                        <button type="button" class="btn-chip active" data-filter="todos">Todos</button>
                    </div>

                    <!-- Cápsula BUSCAR por nombre de paciente o cama -->
                    <div class="search-pill-container">
                        <input
                            type="text"
                            id="searchPatients"
                            class="search-pill-input"
                            placeholder="BUSCAR PACIENTE / CAMA"
                            autocomplete="off">
                        <span class="search-pill-icon" title="Buscar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Seguimiento Clínico -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>ID SEG.</th>
                                <th>PACIENTE</th>
                                <th>UBICACIÓN (CAMA / PISO)</th>
                                <th>FECHA</th>
                                <th>DOLOR (EVA)</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $haySeguimientosBD = false;
                            if (!empty($conectar)) {
                                // Consulta inteligente uniendo seguimiento, atenciones, pacientes y el catálogo de dolor
                                $sqlSeg = "SELECT s.*, 
                                                 p.nombre AS pac_nombre, p.apellido_paterno AS pac_paterno, p.apellido_materno AS pac_materno,
                                                 a.cama AS atn_cama, a.piso AS atn_piso,
                                                 d.Dolor_Eva AS desc_dolor
                                          FROM seguimiento s
                                          LEFT JOIN atencion a ON s.id_atencion = a.id_atencion
                                          LEFT JOIN pacientes p ON a.cedula = p.cedula
                                          LEFT JOIN dolor_eva d ON s.id_dolor = d.id_dolor
                                          ORDER BY s.id_seguimiento DESC";

                                $consultaSeg = @mysqli_query($conectar, $sqlSeg);

                                if ($consultaSeg && mysqli_num_rows($consultaSeg) > 0) {
                                    $haySeguimientosBD = true;
                                    while ($s = mysqli_fetch_assoc($consultaSeg)) {
                                        $idVal = $s['id_seguimiento'] ?? 0;
                                        $idSeg = "SEG-" . str_pad($idVal, 3, '0', STR_PAD_LEFT);

                                        // Obtener nombre del paciente
                                        $nomPac = trim(($s['pac_nombre'] ?? '') . ' ' . ($s['pac_paterno'] ?? '') . ' ' . ($s['pac_materno'] ?? ''));
                                        if (empty($nomPac)) {
                                            $nomPac = $s['paciente'] ?? 'Paciente general';
                                        }

                                        // Ubicación (Cama y Piso)
                                        $cama = !empty($s['atn_cama']) ? $s['atn_cama'] : ($s['cama'] ?? '--');
                                        $piso = !empty($s['atn_piso']) ? "Piso " . $s['atn_piso'] : 'Hospitalización';

                                        $fec = htmlspecialchars($s['fecha'] ?? '');
                                        $dol = htmlspecialchars($s['desc_dolor'] ?? '--');
                            ?>
                                        <tr data-piso="<?= !empty($s['atn_piso']) ? $s['atn_piso'] : '' ?>">
                                            <td class="cell-cedula"><?= $idSeg ?></td>
                                            <td><strong><?= htmlspecialchars($nomPac) ?></strong></td>
                                            <td>
                                                <span class="cama-icon-wrapper">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path>
                                                    </svg>
                                                    Cama <?= $cama ?> (<?= $piso ?>)
                                                </span>
                                            </td>
                                            <td><?= $fec ?></td>
                                            <td><?= $dol ?></td>
                                            <td class="cell-actions">
                                                <a href="ver_seguimiento.php?id_seguimiento=<?= urlencode($idVal) ?>" class="btn-tbl btn-tbl-view" style="text-decoration:none; display:inline-flex; align-items:center;">Ver</a>
                                                <a href="modificar_seguimiento.php?id_seguimiento=<?= urlencode($idVal) ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                                <a href="tablas_seguimiento.php?eliminar_id=<?= urlencode($idVal) ?>" class="btn-tbl btn-tbl-delete" onclick="return confirm('¿Seguro que deseas eliminar este seguimiento clínico?');" style="text-decoration:none; display:inline-flex; align-items:center;">Borrar</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                            }

                            if (!$haySeguimientosBD):
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding: 40px; color: var(--text-secondary);">
                                        No hay registros de seguimiento clínico en la base de datos.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>

    </div>

    <!-- Script de filtrado rápido por texto y ubicación -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchPatients');
            const tableRows = document.querySelectorAll('#patientsTable tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const term = e.target.value.toLowerCase().trim();
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    });
                });
            }

            // Filtros por botones superiores
            document.querySelectorAll('.table-toolbar-left .btn-chip[data-filter]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.table-toolbar-left .btn-chip').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const filter = btn.dataset.filter;

                    tableRows.forEach(row => {
                        if (filter === 'todos') {
                            row.style.display = '';
                        } else {
                            const pisoRow = 'piso-' + row.dataset.piso;
                            row.style.display = (pisoRow === filter) ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
    <script src="dashboard.js"></script>
</body>

</html>