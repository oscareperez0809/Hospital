<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'atencion';
$tituloHeader = 'Control de Atención Médica';

$mensajeExito = '';
$mensajeError = '';

// 1. Procesar Registro o Edición desde formulario_atencion.php (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'])) {
    $idAtencion = intval($_POST['id_atencion'] ?? 0);
    $cedula = trim($_POST['cedula'] ?? '');
    $idDepartamento = intval($_POST['id_departamento'] ?? 0);
    $idEspecialidad = intval($_POST['id_especialidad'] ?? 0);
    $idEtiologia = intval($_POST['id_etiologia'] ?? 0);
    $fechaIngreso = trim($_POST['fecha_ingreso'] ?? date('Y-m-d H:i:s'));
    $fechaEgreso = trim($_POST['fecha_egreso'] ?? '');
    $cama = !empty($_POST['cama']) ? intval($_POST['cama']) : null;
    $piso = !empty($_POST['piso']) ? intval($_POST['piso']) : null;
    $padecimiento = trim($_POST['padecimiento'] ?? '');

    $fechaIngresoVal = !empty($fechaIngreso) ? $fechaIngreso : date('Y-m-d H:i:s');
    $fechaEgresoVal = !empty($fechaEgreso) ? $fechaEgreso : null;

    if (!empty($conectar) && !empty($cedula)) {
        if ($idAtencion > 0) {
            // UPDATE
            $stmt = mysqli_prepare($conectar, "UPDATE atencion SET cedula=?, id_departamento=?, id_especialidad=?, id_etiologia=?, fecha_ingreso=?, fecha_egreso=?, cama=?, piso=?, padecimiento=? WHERE id_atencion=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "siiississi", $cedula, $idDepartamento, $idEspecialidad, $idEtiologia, $fechaIngresoVal, $fechaEgresoVal, $cama, $piso, $padecimiento, $idAtencion);
                if (mysqli_stmt_execute($stmt)) {
                    $mensajeExito = "Atención médica #{$idAtencion} actualizada exitosamente.";
                } else {
                    $mensajeError = "Error al actualizar atención: " . mysqli_error($conectar);
                }
            }
        } else {
            // INSERT
            $stmt = mysqli_prepare($conectar, "INSERT INTO atencion (cedula, id_departamento, id_especialidad, id_etiologia, fecha_ingreso, fecha_egreso, cama, piso, padecimiento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "siiississ", $cedula, $idDepartamento, $idEspecialidad, $idEtiologia, $fechaIngresoVal, $fechaEgresoVal, $cama, $piso, $padecimiento);
                if (mysqli_stmt_execute($stmt)) {
                    $nuevoId = mysqli_insert_id($conectar);
                    $mensajeExito = "Atención médica registrada exitosamente (ID: ATN-" . str_pad($nuevoId, 3, '0', STR_PAD_LEFT) . ").";
                } else {
                    $mensajeError = "Error al registrar atención: " . mysqli_error($conectar);
                }
            }
        }
    }
}

// 2. Procesar Eliminación (?eliminar_id=...)
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
                                        $ing = !empty($a['fecha_ingreso']) ? substr($a['fecha_ingreso'], 0, 10) : 'No registrada';
                                        $egr = !empty($a['fecha_egreso']) ? substr($a['fecha_egreso'], 0, 10) : 'En hospitalización';
                                        $pad = htmlspecialchars($a['padecimiento'] ?? 'Sin descripción');
                            ?>
                                        <tr data-id="<?= $a['id_atencion'] ?>"
                                            data-id-formato="<?= $idAtnFormato ?>"
                                            data-cedula="<?= $ced ?>"
                                            data-paciente="<?= htmlspecialchars($nomPac) ?>"
                                            data-depto="<?= $dep ?>"
                                            data-especialidad="<?= $esp ?>"
                                            data-etiologia="<?= $eti ?>"
                                            data-cama="<?= $cam ?>"
                                            data-piso="<?= $pis ?>"
                                            data-ingreso="<?= $ing ?>"
                                            data-egreso="<?= $egr ?>"
                                            data-padecimiento="<?= $pad ?>">
                                            <td class="cell-cedula"><?= $idAtnFormato ?></td>
                                            <td><?= $ced ?></td>
                                            <td><strong><?= htmlspecialchars($nomPac) ?></strong></td>
                                            <td><?= $dep ?></td>
                                            <td><?= $esp ?></td>
                                            <td><?= $eti ?></td>
                                            <td><?= $cam ?></td>
                                            <td><?= $pis ?></td>
                                            <td class="cell-actions">
                                                <button class="btn-tbl btn-tbl-view btn-view-atn" type="button" title="Ver detalle de atención">Ver</button>
                                                <a href="formulario_atencion.php?id_atencion=<?= urlencode($a['id_atencion']) ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar atención">Editar</a>
                                                <a href="tablas_atencion.php?eliminar_id=<?= urlencode($a['id_atencion']) ?>" class="btn-tbl btn-tbl-delete" onclick="return confirm('¿Seguro que deseas eliminar la atención <?= $idAtnFormato ?> del paciente <?= htmlspecialchars($nomPac) ?>?');" style="text-decoration:none; display:inline-flex; align-items:center;" title="Eliminar atención">Eliminar</a>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }

                            // Estado vacío cuando no hay atenciones registradas
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

    <!-- Modal Detallado: Expediente de Atención Médica -->
    <div id="modalDetalleAtencion" class="modal-backdrop">
        <div class="modal-card" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Expediente de Atención Médica</h3>
                <button type="button" class="modal-close" id="btnCloseAtnModal">&times;</button>
            </div>
            
            <!-- Cabecera de la Atención -->
            <div class="user-detail-header-card">
                <div class="user-detail-avatar" id="atnAvatar">A</div>
                <div class="user-detail-title">
                    <h4 id="atnModalPaciente">Paciente</h4>
                    <p id="atnModalIdBadge">Atención: ATN-000 | Cédula: --</p>
                </div>
            </div>

            <!-- Grilla con la información completa de la tabla atencion -->
            <div class="user-detail-grid">
                <div class="user-detail-item">
                    <div class="user-detail-label">Departamento</div>
                    <div class="user-detail-val" id="atnModalDepto">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Especialidad</div>
                    <div class="user-detail-val" id="atnModalEspecialidad">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Etiología</div>
                    <div class="user-detail-val" id="atnModalEtiologia">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Ubicación (Cama / Piso)</div>
                    <div class="user-detail-val" id="atnModalUbicacion">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Fecha de Ingreso</div>
                    <div class="user-detail-val" id="atnModalIngreso">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Fecha de Egreso</div>
                    <div class="user-detail-val" id="atnModalEgreso">--</div>
                </div>
                <div class="user-detail-item user-detail-full">
                    <div class="user-detail-label">Padecimiento / Diagnóstico</div>
                    <div class="user-detail-val" id="atnModalPadecimiento" style="font-weight: 500; font-size: 13.5px; line-height: 1.45;">--</div>
                </div>
            </div>

            <div class="modal-actions">
                <a href="#" id="btnEditarAtnModal" class="btn-tbl btn-tbl-edit" style="text-decoration:none; padding: 10px 22px; font-size: 13.5px; border-radius: 8px;">Editar Atención</a>
                <button type="button" class="btn-cancel" id="btnCerrarDetalleAtn">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalDetalleAtencion');
            const btnClose = document.getElementById('btnCloseAtnModal');
            const btnCerrar = document.getElementById('btnCerrarDetalleAtn');
            const btnEditarModal = document.getElementById('btnEditarAtnModal');

            function cerrarModal() {
                if (modal) modal.classList.remove('open');
            }

            if (btnClose) btnClose.addEventListener('click', cerrarModal);
            if (btnCerrar) btnCerrar.addEventListener('click', cerrarModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) cerrarModal();
                });
            }

            // Abrir modal con "Ver detalle de atención"
            document.querySelectorAll('.btn-view-atn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    if (!tr) return;

                    const idAtn = tr.dataset.id || '';
                    const idFormato = tr.dataset.idFormato || '';
                    const cedula = tr.dataset.cedula || '';
                    const paciente = tr.dataset.paciente || '';
                    const depto = tr.dataset.depto || '';
                    const especialidad = tr.dataset.especialidad || '';
                    const etiologia = tr.dataset.etiologia || '';
                    const cama = tr.dataset.cama || '--';
                    const piso = tr.dataset.piso || '--';
                    const ingreso = tr.dataset.ingreso || 'No registrada';
                    const egreso = tr.dataset.egreso || 'En hospitalización';
                    const padecimiento = tr.dataset.padecimiento || 'Sin descripción';

                    document.getElementById('atnAvatar').textContent = paciente.charAt(0).toUpperCase() || 'A';
                    document.getElementById('atnModalPaciente').textContent = paciente;
                    document.getElementById('atnModalIdBadge').textContent = `Atención: ${idFormato} | Cédula: ${cedula}`;

                    document.getElementById('atnModalDepto').textContent = depto;
                    document.getElementById('atnModalEspecialidad').textContent = especialidad;
                    document.getElementById('atnModalEtiologia').textContent = etiologia;
                    document.getElementById('atnModalUbicacion').textContent = `Cama: ${cama} (Piso: ${piso})`;
                    document.getElementById('atnModalIngreso').textContent = ingreso;
                    document.getElementById('atnModalEgreso').textContent = egreso;
                    document.getElementById('atnModalPadecimiento').textContent = padecimiento;

                    if (btnEditarModal) {
                        btnEditarModal.href = `formulario_atencion.php?id_atencion=${encodeURIComponent(idAtn)}`;
                    }

                    modal.classList.add('open');
                });
            });
        });
    </script>
</body>

</html>
