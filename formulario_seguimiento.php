<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'seguimiento';
$tituloHeader = 'Registro de Seguimiento Clínico';

// Cargar atenciones activas con los datos del paciente, cama y piso
$atencionesList = [];
if (!empty($conectar)) {
    $sqlAtn = "SELECT a.id_atencion, a.cama, a.piso, 
                      p.nombre, p.apellido_paterno, p.apellido_materno, p.cedula
               FROM atencion a
               LEFT JOIN pacientes p ON a.cedula = p.cedula
               ORDER BY a.id_atencion DESC";
    $resAtn = mysqli_query($conectar, $sqlAtn);
    if ($resAtn) {
        while ($row = mysqli_fetch_assoc($resAtn)) {
            $atencionesList[] = $row;
        }
    }
}

// Procesar inserción si se envía por POST
$mensajeExito = '';
$mensajeError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_atencion'])) {
    $idAtencion = intval($_POST['id_atencion']);
    $dolor = trim($_POST['dolor'] ?? '');
    $bordes = trim($_POST['bordes'] ?? '');
    $exudados = trim($_POST['exudados'] ?? '');
    $eritema = trim($_POST['eritema'] ?? '');
    $fecha = trim($_POST['fecha'] ?? date('Y-m-d'));

    if (!empty($conectar) && $idAtencion > 0) {
        $stmt = mysqli_prepare($conectar, "INSERT INTO seguimiento (id_atencion, fecha, dolor_eva, bordes, exudados, eritema) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isssss", $idAtencion, $fecha, $dolor, $bordes, $exudados, $eritema);
            if (mysqli_stmt_execute($stmt)) {
                $mensajeExito = "Seguimiento clínico registrado exitosamente.";
            } else {
                $mensajeError = "Error al registrar: " . mysqli_stmt_error($stmt);
            }
        }
    } else {
        $mensajeError = "Debe seleccionar un paciente y atención válida.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Seguimiento - ISSSTE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-wrapper">
            <?php include 'header.php'; ?>

            <section class="content-body">
                <div class="form-toolbar">
                    <a href="tablas_seguimiento.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title">Nuevo Seguimiento Clínico</h2>
                        <p class="form-register-subtitle">Seleccione al paciente por su nombre y ubicación hospitalaria (cama/piso).</p>
                    </div>

                    <?php if (!empty($mensajeExito)): ?>
                        <div style="background: rgba(34,197,94,0.15); color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight:600;">✓ <?= $mensajeExito ?></div>
                    <?php endif; ?>
                    <?php if (!empty($mensajeError)): ?>
                        <div style="background: rgba(239,68,68,0.15); color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight:600;">⚠ <?= $mensajeError ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" class="patient-form">

                        <!-- Selector claro por Datos de Paciente y Cama -->
                        <div class="form-field-full" style="margin-bottom: 20px;">
                            <label for="idAtencion" class="form-label">Seleccionar Paciente (por Nombre y Ubicación)</label>
                            <div class="select-with-icon">
                                <select id="idAtencion" name="id_atencion" class="form-control" required>
                                    <option value="" disabled selected>-- Seleccione un paciente hospitalizado --</option>
                                    <?php foreach ($atencionesList as $atn): ?>
                                        <?php
                                        $nombreCompleto = trim(($atn['nombre'] ?? '') . ' ' . ($atn['apellido_paterno'] ?? '') . ' ' . ($atn['apellido_materno'] ?? ''));
                                        if (empty($nombreCompleto)) $nombreCompleto = "Cédula: " . $atn['cedula'];
                                        $camaStr = !empty($atn['cama']) ? "Cama " . $atn['cama'] : "Sin cama";
                                        $pisoStr = !empty($atn['piso']) ? "Piso " . $atn['piso'] : "";
                                        ?>
                                        <option value="<?= $atn['id_atencion'] ?>">
                                            <?= htmlspecialchars($nombreCompleto) ?> — [<?= $camaStr ?> <?= $pisoStr ?>] (Atención ATN-<?= str_pad($atn['id_atencion'], 3, '0', STR_PAD_LEFT) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regFecha" class="form-label">Fecha</label>
                                <input type="date" id="regFecha" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="form-field">
                                <label for="regDolor" class="form-label">Dolor (EVA)</label>
                                <input type="text" id="regDolor" name="dolor" class="form-control" placeholder="Ej. 4 EVA">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regBordes" class="form-label">Bordes</label>
                                <input type="text" id="regBordes" name="bordes" class="form-control" placeholder="Estado de bordes">
                            </div>
                            <div class="form-field">
                                <label for="regExudados" class="form-label">Exudados</label>
                                <input type="text" id="regExudados" name="exudados" class="form-control" placeholder="Características de exudados">
                            </div>
                        </div>

                        <div class="form-field-full">
                            <label for="regEritema" class="form-label">Eritema</label>
                            <textarea id="regEritema" name="eritema" class="form-control form-textarea" rows="3" placeholder="Descripción del eritema"></textarea>
                        </div>

                        <div class="form-actions-right" style="margin-top: 20px; display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-guardar">Guardar Seguimiento</button>
                        </div>

                    </form>
                </div>
            </section>
        </main>
    </div>
    <script src="dashboard.js"></script>
</body>

</html>