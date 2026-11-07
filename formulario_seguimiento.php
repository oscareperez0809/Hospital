<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'seguimiento';
$tituloHeader = 'Registro de Seguimiento Clínico';

function cargarCatalogoSeg($conectar, $tabla, $campoId, $campoTexto)
{
    $lista = [];
    if (!empty($conectar)) {
        $q = @mysqli_query($conectar, "SELECT * FROM `$tabla` ORDER BY `$campoTexto` ASC");
        if ($q && mysqli_num_rows($q) > 0) {
            while ($r = mysqli_fetch_assoc($q)) $lista[] = $r;
        }
    }
    return $lista;
}

$atencionesList = [];
if (!empty($conectar)) {
    $sqlAtn = "SELECT a.id_atencion, a.cama, a.piso, p.nombre, p.apellido_paterno, p.apellido_materno, p.cedula 
               FROM atencion a LEFT JOIN pacientes p ON a.cedula = p.cedula ORDER BY a.id_atencion DESC";
    $res = mysqli_query($conectar, $sqlAtn);
    if ($res) while ($row = mysqli_fetch_assoc($res)) $atencionesList[] = $row;
}

$bordesList = cargarCatalogoSeg($conectar, 'bordes', 'id_borde', 'bordes');
$exudadosList = cargarCatalogoSeg($conectar, 'exudados', 'id_exudados', 'exudado');
$dolorList = cargarCatalogoSeg($conectar, 'dolor_eva', 'id_dolor', 'Dolor_Eva');
$eritemasList = cargarCatalogoSeg($conectar, 'eritemas', 'id_eritema', 'eritema');

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_atencion'])) {
    $idAtencion = intval($_POST['id_atencion']);
    $fecha = trim($_POST['fecha'] ?? date('Y-m-d H:i:s'));

    $idBorde = !empty($_POST['id_borde']) ? intval($_POST['id_borde']) : null;
    $idExudado = !empty($_POST['id_exudado']) ? intval($_POST['id_exudado']) : null;
    $idDolor = !empty($_POST['id_dolor']) ? intval($_POST['id_dolor']) : null;
    $idEritema = !empty($_POST['id_eritema']) ? intval($_POST['id_eritema']) : null;

    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!empty($conectar) && $idAtencion > 0) {
        $stmt = mysqli_prepare($conectar, "INSERT INTO seguimiento (id_atencion, fecha, id_borde, id_exudado, id_dolor, id_eritema, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isiiiis", $idAtencion, $fecha, $idBorde, $idExudado, $idDolor, $idEritema, $observaciones);
            if (mysqli_stmt_execute($stmt)) {
                $mensajeExito = "Seguimiento clínico registrado exitosamente.";
            } else {
                $mensajeError = "Error al guardar: " . mysqli_stmt_error($stmt);
            }
        }
    } else {
        $mensajeError = "Debe seleccionar un paciente/atención válido.";
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
                        <p class="form-register-subtitle">Complete la información clínica del paciente y evolución hospitalaria.</p>
                    </div>

                    <?php if (!empty($mensajeExito)): ?>
                        <div style="background-color: rgba(34, 197, 94, 0.15); border: 1.5px solid #22c55e; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                            ✓ <?= $mensajeExito ?> <a href="tablas_seguimiento.php" style="color: inherit; text-decoration: underline; margin-left: 10px;">Ver listado</a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mensajeError)): ?>
                        <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                            ⚠ <?= $mensajeError ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="patient-form">

                        <!-- Fila 1: Paciente / Ubicación y Fecha -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Paciente / Ubicación Hospitalaria</label>
                                <div class="select-with-icon">
                                    <select name="id_atencion" class="form-control" required>
                                        <option value="" disabled selected>-- Seleccione paciente hospitalizado --</option>
                                        <?php foreach ($atencionesList as $atn): ?>
                                            <?php
                                            $nom = trim(($atn['nombre'] ?? '') . ' ' . ($atn['apellido_paterno'] ?? '') . ' ' . ($atn['apellido_materno'] ?? ''));
                                            if (empty($nom)) $nom = "Cédula: " . $atn['cedula'];
                                            $cam = !empty($atn['cama']) ? "Cama " . $atn['cama'] : "S/C";
                                            $pis = !empty($atn['piso']) ? "Piso " . $atn['piso'] : "";
                                            ?>
                                            <option value="<?= $atn['id_atencion'] ?>">
                                                <?= htmlspecialchars($nom) ?> [<?= $cam ?> <?= $pis ?>] (ATN-<?= str_pad($atn['id_atencion'], 3, '0', STR_PAD_LEFT) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Fecha y Hora</label>
                                <div class="input-with-icon">
                                    <input type="datetime-local" name="fecha" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2: Bordes y Exudados -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Bordes</label>
                                <div class="select-with-icon">
                                    <select name="id_borde" class="form-control">
                                        <option value="">-- Seleccione estado de bordes --</option>
                                        <?php foreach ($bordesList as $b): ?>
                                            <option value="<?= $b['id_borde'] ?>"><?= htmlspecialchars($b['bordes']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Exudados</label>
                                <div class="select-with-icon">
                                    <select name="id_exudado" class="form-control">
                                        <option value="">-- Seleccione tipo de exudado --</option>
                                        <?php foreach ($exudadosList as $e): ?>
                                            <option value="<?= $e['id_exudados'] ?>"><?= htmlspecialchars($e['exudado']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: Dolor (EVA) y Eritema -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Dolor (EVA)</label>
                                <div class="select-with-icon">
                                    <select name="id_dolor" class="form-control">
                                        <option value="">-- Seleccione escala EVA --</option>
                                        <?php foreach ($dolorList as $d): ?>
                                            <option value="<?= $d['id_dolor'] ?>"><?= htmlspecialchars($d['Dolor_Eva']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Eritema</label>
                                <div class="select-with-icon">
                                    <select name="id_eritema" class="form-control">
                                        <option value="">-- Seleccione eritema --</option>
                                        <?php foreach ($eritemasList as $er): ?>
                                            <option value="<?= $er['id_eritema'] ?>"><?= htmlspecialchars($er['eritema']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Observaciones / Notas clínicas -->
                        <div class="form-field-full">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control form-textarea" rows="3" placeholder="Observaciones clínicas adicionales"></textarea>
                        </div>

                        <!-- Fila 5: Botón Guardar -->
                        <div class="form-row-bottom" style="display: flex; justify-content: flex-end; margin-top: 20px;">
                            <div class="form-actions-right">
                                <button type="submit" class="btn-guardar">
                                    Guardar Seguimiento
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </section>
        </main>
    </div>
    <script src="dashboard.js"></script>
</body>

</html>