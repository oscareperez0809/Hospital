<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'seguimiento';
$tituloHeader = 'Expediente de Seguimiento Clínico';

$idSeguimiento = intval($_GET['id_seguimiento'] ?? 0);
$seg = null;

if ($idSeguimiento > 0 && !empty($conectar)) {
  $stmt = mysqli_prepare($conectar, "SELECT * FROM seguimiento WHERE id_seguimiento = ? LIMIT 1");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idSeguimiento);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res && mysqli_num_rows($res) > 0) {
      $seg = mysqli_fetch_assoc($res);
    }
  }
}

if (!$seg) {
  header("Location: tablas_seguimiento.php");
  exit;
}

$idSegFormato = "SEG-" . str_pad($seg['id_seguimiento'], 3, '0', STR_PAD_LEFT);
$paciente = htmlspecialchars($seg['paciente'] ?? '');
$inicialAvatar = mb_strtoupper(mb_substr($paciente, 0, 1));
$cama = htmlspecialchars($seg['cama'] ?? '--');
$fecha = htmlspecialchars($seg['fecha'] ?? '--');
$dolor = htmlspecialchars($seg['dolor_eva'] ?? ($seg['dolor'] ?? '--'));
$bordes = htmlspecialchars($seg['bordes'] ?? 'No especificado');
$exudados = htmlspecialchars($seg['exudados'] ?? 'No especificado');
$eritema = htmlspecialchars($seg['eritema'] ?? 'No especificado');
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

      <section class="content-body" style="display: flex; justify-content: center; align-items: flex-start; padding-top: 40px;">
        <div class="modal-backdrop open" style="position: relative; background: transparent; backdrop-filter: none; display: flex; justify-content: center; align-items: center; width: 100%;">
          <div class="modal-card" style="max-width: 600px; width: 100%;">

            <div class="modal-header">
              <h3>Detalle de Seguimiento Clínico</h3>
              <a href="tablas_seguimiento.php" class="modal-close" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">&times;</a>
            </div>

            <div class="user-detail-header-card">
              <div class="user-detail-avatar"><?= $inicialAvatar ?></div>
              <div class="user-detail-title">
                <h4><?= $paciente ?></h4>
                <p>Registro: <?= $idSegFormato ?> | Cama: <?= $cama ?></p>
              </div>
            </div>

            <div class="user-detail-grid">
              <div class="user-detail-item">
                <div class="user-detail-label">Fecha de Registro</div>
                <div class="user-detail-val"><?= $fecha ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Dolor (EVA)</div>
                <div class="user-detail-val"><?= $dolor ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Bordes</div>
                <div class="user-detail-val"><?= $bordes ?></div>
              </div>
              <div class="user-detail-item">
                <div class="user-detail-label">Exudados</div>
                <div class="user-detail-val"><?= $exudados ?></div>
              </div>
              <div class="user-detail-item user-detail-full">
                <div class="user-detail-label">Eritema</div>
                <div class="user-detail-val" style="font-weight: 500; font-size: 13.5px; line-height: 1.45;"><?= $eritema ?></div>
              </div>
            </div>

            <div class="modal-actions">
              <a href="modificar_seguimiento.php?id_seguimiento=<?= $seg['id_seguimiento'] ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none; padding: 10px 22px; font-size: 13.5px; border-radius: 8px;">Editar Seguimiento</a>
              <a href="tablas_seguimiento.php" class="btn-cancel" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">Regresar</a>
            </div>

          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="dashboard.js"></script>
</body>

</html>