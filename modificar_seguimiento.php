<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'seguimiento';
$tituloHeader = 'Modificar Seguimiento Clínico';

$idSeguimiento = intval($_GET['id_seguimiento'] ?? 0);
if ($idSeguimiento <= 0) {
    header("Location: tablas_seguimiento.php?error=1");
    exit();
}

function cargarCatalogoSeg($conectar, $tabla, $campoId, $campoTexto)
{
    $lista = [];
    if (!empty($conectar)) {
        $q = @mysqli_query($conectar, "SELECT * FROM `$tabla` ORDER BY `$campoTexto` ASC");
        if ($q && mysqli_num_rows($q) > 0) while ($r = mysqli_fetch_assoc($q)) $lista[] = $r;
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

$stmt = mysqli_prepare($conectar, "SELECT * FROM seguimiento WHERE id_seguimiento = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $idSeguimiento);
mysqli_stmt_execute($stmt);
$seg = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$seg) {
    header("Location: tablas_seguimiento.php?error=1");
    exit();
}

$idAtencion = $seg['id_atencion'];
$fecha = !empty($seg['fecha']) ? date('Y-m-d\TH:i', strtotime($seg['fecha'])) : '';
$idBorde = $seg['id_borde'];
$idExudado = $seg['id_exudado'];
$idDolor = $seg['id_dolor'];
$idEritema = $seg['id_eritema'];
$observaciones = $seg['observaciones'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Seguimiento - ISSSTE</title>
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
                        <h2 class="form-register-title">Editar Seguimiento Clínico (SEG-<?= str_pad($idSeguimiento, 3, '0', STR_PAD_LEFT) ?>)</h2>
                        <p class="form-register-subtitle">Modifique la información clínica y evolución del paciente.</p>
                    </div>

                    <form action="actualizar_seguimiento.php" method="POST" class="patient-form">

                        <input type="hidden" name="id_seguimiento" value="<?= $idSeguimiento ?>">

                        <!-- Fila 1: Paciente / Ubicación y Fecha -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Paciente / Ubicación Hospitalaria</label>
                                <div class="select-with-icon">
                                    <select name="id_atencion" class="form-control" required>
                                        <?php foreach ($atencionesList as $atn): ?>
                                            <?php
                                            $nom = trim(($atn['nombre'] ?? '') . ' ' . ($atn['apellido_paterno'] ?? '') . ' ' . ($atn['apellido_materno'] ?? ''));
                                            if (empty($nom)) $nom = "Cédula: " . $atn['cedula'];
                                            $cam = !empty($atn['cama']) ? "Cama " . $atn['cama'] : "S/C";
                                            $pis = !empty($atn['piso']) ? "Piso " . $atn['piso'] : "";
                                            $sel = ($atn['id_atencion'] == $idAtencion) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $atn['id_atencion'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($nom) ?> [<?= $cam ?> <?= $pis ?>] (ATN-<?= str_pad($atn['id_atencion'], 3, '0', STR_PAD_LEFT) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Fecha y Hora</label>
                                <div class="input-with-icon">
                                    <input type="datetime-local" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha) ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2: Bordes y Exudados -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Bordes</label>
                                <div class="select-with-icon">
                                    <select name="id_borde" class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($bordesList as $b): ?>
                                            <option value="<?= $b['id_borde'] ?>" <?= ($b['id_borde'] == $idBorde) ? 'selected' : '' ?>><?= htmlspecialchars($b['bordes']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Exudados</label>
                                <div class="select-with-icon">
                                    <select name="id_exudado" class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($exudadosList as $e): ?>
                                            <option value="<?= $e['id_exudados'] ?>" <?= ($e['id_exudados'] == $idExudado) ? 'selected' : '' ?>><?= htmlspecialchars($e['exudado']) ?></option>
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
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($dolorList as $d): ?>
                                            <option value="<?= $d['id_dolor'] ?>" <?= ($d['id_dolor'] == $idDolor) ? 'selected' : '' ?>><?= htmlspecialchars($d['Dolor_Eva']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Eritema</label>
                                <div class="select-with-icon">
                                    <select name="id_eritema" class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($eritemasList as $er): ?>
                                            <option value="<?= $er['id_eritema'] ?>" <?= ($er['id_eritema'] == $idEritema) ? 'selected' : '' ?>><?= htmlspecialchars($er['eritema']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Observaciones / Notas clínicas -->
                        <div class="form-field-full">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control form-textarea" rows="3"><?= htmlspecialchars($observaciones) ?></textarea>
                        </div>

                        <!-- Fila 5: Botón Actualizar -->
                        <div class="form-row-bottom" style="display: flex; justify-content: flex-end; margin-top: 20px;">
                            <div class="form-actions-right">
                                <button type="submit" class="btn-guardar">
                                    Actualizar Seguimiento
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