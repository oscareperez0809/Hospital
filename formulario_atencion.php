<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'atencion';
$tituloHeader = 'Registro de Atención Médica';

$mensajeExito = '';
$mensajeError = '';

// 1. Procesar Registro (INSERT) o Edición (UPDATE) cuando se envía el formulario
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
                    $mensajeError = "Error al actualizar atención: " . mysqli_stmt_error($stmt);
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
                    $mensajeError = "Error al registrar atención: " . mysqli_stmt_error($stmt);
                }
            }
        }
    } else {
        $mensajeError = "La cédula del paciente es obligatoria.";
    }
}

// Función para obtener opciones relacionales
function cargarCatalogo($conectar, $tabla, $campoId, $campoTexto, $defaults = [])
{
    $lista = [];
    if (!empty($conectar)) {
        $q = @mysqli_query($conectar, "SELECT * FROM `$tabla` ORDER BY `$campoTexto` ASC");
        if ($q && mysqli_num_rows($q) > 0) {
            while ($r = mysqli_fetch_assoc($q)) $lista[] = $r;
        } elseif (!empty($defaults)) {
            foreach ($defaults as $def) {
                @mysqli_query($conectar, "INSERT INTO `$tabla` (`$campoTexto`) VALUES ('" . mysqli_real_escape_string($conectar, $def) . "')");
            }
            $q = @mysqli_query($conectar, "SELECT * FROM `$tabla` ORDER BY `$campoTexto` ASC");
            if ($q) {
                while ($r = mysqli_fetch_assoc($q)) $lista[] = $r;
            }
        }
    }
    return $lista;
}

// Cargar catálogos relacionales de MySQL
$deptos = cargarCatalogo($conectar, 'departamentos', 'id_departamento', 'departamento', [
    'Urgencias',
    'Cirugía General',
    'Medicina Interna',
    'Pediatría',
    'Ginecología y Obstetricia',
    'Oncología',
    'Traumatología'
]);

$especialidades = cargarCatalogo($conectar, 'especialidades', 'id_especialidad', 'especialidad', [
    'Medicina Interna',
    'Cardiología',
    'Pediatría',
    'Obstetricia',
    'Traumatología',
    'Oncología Médica',
    'Cirugía General'
]);

$etiologias = cargarCatalogo($conectar, 'etiologia', 'id_etiologia', 'etiologia', [
    'Infecciosa',
    'Traumática',
    'Quirúrgica',
    'Degenerativa',
    'Vascular',
    'Congénita',
    'Oncológica'
]);

// Cargar lista de pacientes registrados
$pacientesList = [];
if (!empty($conectar)) {
    $qPac = @mysqli_query($conectar, "SELECT cedula, nombre, apellido_paterno, apellido_materno FROM pacientes ORDER BY nombre ASC");
    if ($qPac) {
        while ($p = mysqli_fetch_assoc($qPac)) $pacientesList[] = $p;
    }
}

// Valores por si se viene de editar
$idAtencion = intval($_GET['id_atencion'] ?? 0);
$cedula = '';
$idDepartamento = 0;
$idEspecialidad = 0;
$idEtiologia = 0;
$fechaIngreso = date('Y-m-d');
$fechaEgreso = '';
$cama = '';
$piso = '';
$padecimiento = '';

if ($idAtencion > 0 && !empty($conectar)) {
    $stmt = mysqli_prepare($conectar, "SELECT * FROM atencion WHERE id_atencion = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $idAtencion);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res && $atn = mysqli_fetch_assoc($res)) {
            $cedula = $atn['cedula'] ?? '';
            $idDepartamento = intval($atn['id_departamento'] ?? 0);
            $idEspecialidad = intval($atn['id_especialidad'] ?? 0);
            $idEtiologia = intval($atn['id_etiologia'] ?? 0);
            $fechaIngreso = !empty($atn['fecha_ingreso']) ? substr($atn['fecha_ingreso'], 0, 10) : $fechaIngreso;
            $fechaEgreso = !empty($atn['fecha_egreso']) ? substr($atn['fecha_egreso'], 0, 10) : '';
            $cama = $atn['cama'] ?? '';
            $piso = $atn['piso'] ?? '';
            $padecimiento = $atn['padecimiento'] ?? '';
        }
    }
}
$esEdicion = ($idAtencion > 0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar Atención' : 'Crear Atención' ?> - ISSSTE</title>
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

            <!-- Formulario Crear Atención -->
            <section class="content-body">

                <!-- Alertas de éxito o error -->
                <?php if (!empty($mensajeExito)): ?>
                    <div style="background-color: rgba(34, 197, 94, 0.15); border: 1.5px solid #22c55e; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                        ✓ <?= htmlspecialchars($mensajeExito) ?> <a href="tablas_atencion.php" style="color: inherit; text-decoration: underline; margin-left: 10px;">Ver listado</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                    <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                        ⚠ <?= htmlspecialchars($mensajeError) ?>
                    </div>
                <?php endif; ?>

                <!-- Botón VOLVER rectangular gris -->
                <div class="form-toolbar">
                    <a href="tablas_atencion.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <!-- Tarjeta Blanca de Registro de Atención -->
                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title"><?= $esEdicion ? 'Editar Atención Médica' : 'Crear Atención Médica' ?></h2>
                        <p class="form-register-subtitle">Complete la información clínica del paciente y asignación hospitalaria.</p>
                    </div>

                    <!-- Cambiado action="" para procesar en el mismo archivo -->
                    <form action="" method="POST" class="patient-form">

                        <?php if ($esEdicion): ?>
                            <input type="hidden" name="id_atencion" value="<?= $idAtencion ?>">
                        <?php endif; ?>

                        <!-- Fila 1: Paciente (Cédula) y Departamento -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnCedula" class="form-label">Paciente / Cédula</label>
                                <div class="select-with-icon">
                                    <select id="atnCedula" name="cedula" class="form-control" required>
                                        <option value="" disabled <?= empty($cedula) ? 'selected' : '' ?>>Seleccione un paciente registrado</option>
                                        <?php if (!empty($pacientesList)): ?>
                                            <?php foreach ($pacientesList as $pac): ?>
                                                <?php $sel = ($pac['cedula'] === $cedula) ? 'selected' : ''; ?>
                                                <option value="<?= htmlspecialchars($pac['cedula']) ?>" <?= $sel ?>>
                                                    <?= htmlspecialchars($pac['nombre'] . ' ' . ($pac['apellido_paterno'] ?? '') . ' ' . ($pac['apellido_materno'] ?? '')) ?> (Céd. <?= htmlspecialchars($pac['cedula']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No hay pacientes registrados</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="atnDepto" class="form-label">Departamento</label>
                                <div class="select-with-icon">
                                    <select id="atnDepto" name="id_departamento" class="form-control" required>
                                        <option value="" disabled <?= ($idDepartamento === 0) ? 'selected' : '' ?>>Seleccione un departamento</option>
                                        <?php foreach ($deptos as $d): ?>
                                            <?php $sel = (intval($d['id_departamento']) === $idDepartamento) ? 'selected' : ''; ?>
                                            <option value="<?= $d['id_departamento'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($d['departamento']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2: Especialidad y Etiología -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnEspecialidad" class="form-label">Especialidad</label>
                                <div class="select-with-icon">
                                    <select id="atnEspecialidad" name="id_especialidad" class="form-control" required>
                                        <option value="" disabled <?= ($idEspecialidad === 0) ? 'selected' : '' ?>>Seleccione una especialidad</option>
                                        <?php foreach ($especialidades as $e): ?>
                                            <?php $sel = (intval($e['id_especialidad']) === $idEspecialidad) ? 'selected' : ''; ?>
                                            <option value="<?= $e['id_especialidad'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($e['especialidad']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="atnEtiologia" class="form-label">Etiología</label>
                                <div class="select-with-icon">
                                    <select id="atnEtiologia" name="id_etiologia" class="form-control" required>
                                        <option value="" disabled <?= ($idEtiologia === 0) ? 'selected' : '' ?>>Seleccione una etiología</option>
                                        <?php foreach ($etiologias as $et): ?>
                                            <?php $sel = (intval($et['id_etiologia']) === $idEtiologia) ? 'selected' : ''; ?>
                                            <option value="<?= $et['id_etiologia'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($et['etiologia']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: Fecha de Ingreso y Fecha de Egreso -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnIngreso" class="form-label">Fecha de Ingreso</label>
                                <div class="input-with-icon">
                                    <input
                                        type="date"
                                        id="atnIngreso"
                                        name="fecha_ingreso"
                                        class="form-control"
                                        value="<?= htmlspecialchars($fechaIngreso) ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="atnEgreso" class="form-label">Fecha de Egreso (Opcional)</label>
                                <div class="input-with-icon">
                                    <input
                                        type="date"
                                        id="atnEgreso"
                                        name="fecha_egreso"
                                        class="form-control"
                                        value="<?= htmlspecialchars($fechaEgreso) ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Padecimiento (Textarea) -->
                        <div class="form-field-full">
                            <label for="atnPadecimiento" class="form-label">Padecimiento / Diagnóstico</label>
                            <textarea
                                id="atnPadecimiento"
                                name="padecimiento"
                                class="form-control form-textarea"
                                placeholder="Describa el diagnóstico o motivo de ingreso"
                                rows="3"><?= htmlspecialchars($padecimiento) ?></textarea>
                        </div>

                        <!-- Fila 5: Cama, Piso y Botón Guardar -->
                        <div class="form-row-2" style="align-items: flex-end;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-field">
                                    <label for="atnCama" class="form-label">Cama</label>
                                    <input
                                        type="number"
                                        id="atnCama"
                                        name="cama"
                                        class="form-control"
                                        placeholder="Ej. 205"
                                        value="<?= htmlspecialchars($cama) ?>">
                                </div>
                                <div class="form-field">
                                    <label for="atnPiso" class="form-label">Piso</label>
                                    <input
                                        type="number"
                                        id="atnPiso"
                                        name="piso"
                                        class="form-control"
                                        placeholder="Ej. 2"
                                        value="<?= htmlspecialchars($piso) ?>">
                                </div>
                            </div>
                            <div class="form-actions-btn" style="display: flex; justify-content: flex-end;">
                                <button type="submit" class="btn-guardar">
                                    <?= $esEdicion ? 'Actualizar Atención' : 'Guardar Atención' ?>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </section>
        </main>

    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
</body>

</html>