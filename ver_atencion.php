<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'atencion';
$tituloHeader = 'Expediente de Atención Médica';

$idAtencion = intval($_GET['id_atencion'] ?? 0);
$atencion = null;
$errorSql = '';

if ($idAtencion > 0 && !empty($conectar)) {
  $sql = "SELECT a.*, 
                   p.nombre AS pac_nombre, p.apellido_paterno AS pac_paterno, p.apellido_materno AS pac_materno,
                   d.departamento, 
                   e.especialidad, 
                   et.etiologia
            FROM atencion a
            LEFT JOIN pacientes p ON a.cedula = p.cedula
            LEFT JOIN departamentos d ON a.id_departamento = d.id_departamento
            LEFT JOIN especialidades e ON a.id_especialidad = e.id_especialidad
            LEFT JOIN etiologia et ON a.id_etiologia = et.id_etiologia
            WHERE a.id_atencion = ?";

  $stmt = mysqli_prepare($conectar, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idAtencion);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res && mysqli_num_rows($res) > 0) {
      $atencion = mysqli_fetch_assoc($res);
    } else {
      $errorSql = "No se encontró ningún registro con el ID de atención: " . $idAtencion;
    }
  } else {
    $errorSql = "Error en la consulta SQL: " . mysqli_error($conectar);
  }
} else {
  $errorSql = "ID de atención inválido o no proporcionado.";
}

// Si ocurre un error, se muestra en pantalla en lugar de redirigir silenciosamente
if (!$atencion) {
?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="UTF-8">
    <title>Error - Ver Atención</title>
    <link rel="stylesheet" href="dashboard.css">
  </head>

  <body style="padding: 40px; font-family: 'Montserrat', sans-serif; background: var(--bg-main, #f4f6f9); color: var(--text-primary, #333);">
    <div style="max-width: 500px; margin: 0 auto; background: var(--bg-card, #fff); padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
      <h3 style="color: #ef4444; margin-bottom: 15px;">No se pudo cargar el expediente</h3>
      <p style="margin-bottom: 20px; font-size: 14px;"><?= htmlspecialchars($errorSql) ?></p>
      <a href="tablas_atencion.php" class="btn-tbl btn-tbl-edit" style="text-decoration:none; padding: 10px 20px; border-radius: 8px; display:inline-block;">Regresar al Listado</a>
    </div>
  </body>

  </html>
<?php
  exit;
}

$idAtnFormato = "ATN-" . str_pad($atencion['id_atencion'], 3, '0', STR_PAD_LEFT);
$ced = htmlspecialchars($atencion['cedula']);
$nomPac = trim(($atencion['pac_nombre'] ?? '') . ' ' . ($atencion['pac_paterno'] ?? '') . ' ' . ($atencion['pac_materno'] ?? ''));
if (empty($nomPac)) $nomPac = "Paciente Céd. $ced";
$inicialAvatar = mb_strtoupper(mb_substr($nomPac, 0, 1));
$dep = htmlspecialchars($atencion['departamento'] ?? 'No asignado');
$esp = htmlspecialchars($atencion['especialidad'] ?? 'General');
$eti = htmlspecialchars($atencion['etiologia'] ?? 'No especificada');
$cam = !empty($atencion['cama']) ? htmlspecialchars($atencion['cama']) : '--';
$pis = !empty($atencion['piso']) ? htmlspecialchars($atencion['piso']) : '--';
$ing = !empty($atencion['fecha_ingreso']) ? $atencion['fecha_ingreso'] : 'No registrada';
$egr = !empty($atencion['fecha_egreso']) ? $atencion['fecha_egreso'] : 'En hospitalización';
$pad = htmlspecialchars($atencion['padecimiento'] ?? 'Sin descripción');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ver Atención - ISSSTE</title>
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
      <section class="content-body" style="display: flex; justify-content: center; align-items: flex-start; padding-top: 40px;">
        <div class="modal-backdrop open" style="position: relative; background: transparent; backdrop-filter: none; display: flex; justify-content: center; align-items: center; width: 100%;">
          <div class="modal-card" style="max-width: 600px; width: 100%;">
            <div class="modal-header">
              <h3>Expediente de Atención Médica</h3>
              <a href="tablas_atencion.php" class="modal-close" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">&times;</a>
            </div>
            <div class="user-detail-header-card">
              <div class="user-detail-avatar"><?= $inicialAvatar ?></div>
              <div class="user-detail-title">
                <h4><?= $nomPac ?></h4>
                <p>Atención: <?= $idAtnFormato ?> | Cédula: <?= $ced ?></p>
              </div>
            </div>
            <div class="user-detail-grid">
              <div class="user-detail-item">
                <div class="user-detail-label">Departamento</div>
                <div class="user-detail-val"><?= $dep ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Especialidad</div>
                <div class="user-detail-val"><?= $esp ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Etiología</div>
                <div class="user-detail-val"><?= $eti ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Ubicación (Cama / Piso)</div>
                <div class="user-detail-val">Cama: <?= $cam ?> (Piso: <?= $pis ?>)</div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Fecha de Ingreso</div>
                <div class="user-detail-val"><?= $ing ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Fecha de Egreso</div>
                <div class="user-detail-val"><?= $egr ?></div>
              </div>
              <div class="user-detail-item user-detail-full">
                <div class="user-detail-label">Padecimiento / Diagnóstico</div>
                <div class="user-detail-val" style="font-weight: 500; font-size: 13.5px; line-height: 1.45;"><?= $pad ?></div>
              </div>
            </div>
            <div class="modal-actions">
              <a href="modificar_atencion.php?id_atencion=<?= $atencion['id_atencion'] ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none; padding: 10px 22px; font-size: 13.5px; border-radius: 8px;">Editar Atención</a>
              <a href="tablas_atencion.php" class="btn-cancel" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">Regresar</a>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script src="dashboard.js"></script>
</body>

</html>