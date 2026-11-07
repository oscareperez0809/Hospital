<?php
// Activar reporte de errores de PHP para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "conexion.php";

$paginaActiva = 'seguimiento';
$tituloHeader = 'Detalle de Seguimiento Clínico';

$idSeguimiento = intval($_GET['id_seguimiento'] ?? 0);

if ($idSeguimiento <= 0) {
  die("<div style='padding: 20px; color: red; font-family: sans-serif;'><h3>Error:</h3> ID de seguimiento no válido o no recibido en la URL.</div>");
}

if (empty($conectar)) {
  die("<div style='padding: 20px; color: red; font-family: sans-serif;'><h3>Error:</h3> No hay conexión a la base de datos establecida en conexion.php.</div>");
}

$seg = null;

// Consulta detallada con manejo de errores explícito
$sqlVer = "SELECT s.*, 
            p.nombre AS pac_nombre, p.apellido_paterno AS pac_paterno, p.apellido_materno AS pac_materno, p.cedula AS pac_cedula,
            a.cama AS atn_cama, a.piso AS atn_piso,
            b.bordes AS desc_borde,
            e.exudado AS desc_exudado,
            d.Dolor_Eva AS desc_dolor,
            er.eritema AS desc_eritema
     FROM seguimiento s
     LEFT JOIN atencion a ON s.id_atencion = a.id_atencion
     LEFT JOIN pacientes p ON a.cedula = p.cedula
     LEFT JOIN bordes b ON s.id_borde = b.id_borde
     LEFT JOIN exudados e ON s.id_exudado = e.id_exudados
     LEFT JOIN dolor_eva d ON s.id_dolor = d.id_dolor
     LEFT JOIN eritemas er ON s.id_eritema = er.id_eritema
     WHERE s.id_seguimiento = ? LIMIT 1";

$stmt = mysqli_prepare($conectar, $sqlVer);
if (!$stmt) {
  die("<div style='padding: 20px; color: red; font-family: sans-serif;'><h3>Error en la consulta SQL (Prepare):</h3> " . mysqli_error($conectar) . "</div>");
}

mysqli_stmt_bind_param($stmt, "i", $idSeguimiento);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) > 0) {
  $seg = mysqli_fetch_assoc($res);
} else {
  die("<div style='padding: 20px; color: orange; font-family: sans-serif;'><h3>Aviso:</h3> No se encontró ningún registro en la base de datos con el ID <b>$idSeguimiento</b>.</div>");
}

$idSeg = "SEG-" . str_pad($seg['id_seguimiento'], 3, '0', STR_PAD_LEFT);
$nombrePac = trim(($seg['pac_nombre'] ?? '') . ' ' . ($seg['pac_paterno'] ?? '') . ' ' . ($seg['pac_materno'] ?? ''));
if (empty($nombrePac)) $nombrePac = "Cédula: " . ($seg['pac_cedula'] ?? 'S/N');
$cama = !empty($seg['atn_cama']) ? "Cama " . $seg['atn_cama'] : "S/C";
$piso = !empty($seg['atn_piso']) ? "Piso " . $seg['atn_piso'] : "Hospitalización";
$fecha = !empty($seg['fecha']) ? date('d/m/Y H:i', strtotime($seg['fecha'])) : '--';
$borde = !empty($seg['desc_borde']) ? $seg['desc_borde'] : 'No especificado';
$exudado = !empty($seg['desc_exudado']) ? $seg['desc_exudado'] : 'No especificado';
$dolor = !empty($seg['desc_dolor']) ? $seg['desc_dolor'] : 'No especificado';
$eritema = !empty($seg['desc_eritema']) ? $seg['desc_eritema'] : 'No especificado';
$observaciones = !empty($seg['observaciones']) ? $seg['observaciones'] : 'Sin observaciones registradas.';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ver Seguimiento - ISSSTE</title>
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
            <h2 class="form-register-title">Evaluación Clínica (<?= $idSeg ?>)</h2>
            <p class="form-register-subtitle">Detalles registrados para el paciente en hospitalización.</p>
          </div>

          <div class="patient-form">
            <div class="form-row-2">
              <div class="form-field">
                <label class="form-label">Paciente</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <strong><?= htmlspecialchars($nombrePac) ?></strong>
                </div>
              </div>
              <div class="form-field">
                <label class="form-label">Ubicación Hospitalaria</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($cama) ?> (<?= htmlspecialchars($piso) ?>)
                </div>
              </div>
            </div>

            <div class="form-row-2">
              <div class="form-field">
                <label class="form-label">Fecha y Hora de Evaluación</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($fecha) ?>
                </div>
              </div>
              <div class="form-field">
                <label class="form-label">Escala de Dolor (EVA)</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($dolor) ?>
                </div>
              </div>
            </div>

            <div class="form-row-2">
              <div class="form-field">
                <label class="form-label">Estado de Bordes</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($borde) ?>
                </div>
              </div>
              <div class="form-field">
                <label class="form-label">Tipo de Exudado</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($exudado) ?>
                </div>
              </div>
            </div>

            <div class="form-row-2">
              <div class="form-field">
                <label class="form-label">Tipo de Eritema</label>
                <div class="form-control" style="background: var(--bg-main); display: flex; align-items: center;">
                  <?= htmlspecialchars($eritema) ?>
                </div>
              </div>
            </div>

            <div class="form-field-full">
              <label class="form-label">Observaciones Clínicas</label>
              <div class="form-control form-textarea" style="background: var(--bg-main); height: auto; min-height: 80px; padding: 12px; line-height: 1.5;">
                <?= nl2br(htmlspecialchars($observaciones)) ?>
              </div>
            </div>

            <div class="form-row-bottom" style="display: flex; justify-content: flex-end; margin-top: 25px; gap: 10px;">
              <a href="modificar_seguimiento.php?id_seguimiento=<?= $seg['id_seguimiento'] ?>" class="btn-guardar" style="text-decoration: none; display: inline-flex; align-items: center;">
                Modificar Registro
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script src="dashboard.js"></script>
</body>

</html>