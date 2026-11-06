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

$stmt = mysqli_prepare($conectar, "SELECT * FROM seguimiento WHERE id_seguimiento = ? LIMIT 1");
if (!$stmt) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

mysqli_stmt_bind_param($stmt, "i", $idSeguimiento);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$seg = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$seg) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

$paciente = $seg['paciente'] ?? '';
$cama = $seg['cama'] ?? '';
$fecha = $seg['fecha'] ?? '';
$dolor = $seg['dolor_eva'] ?? ($seg['dolor'] ?? '');
$bordes = $seg['bordes'] ?? '';
$exudados = $seg['exudados'] ?? '';
$eritema = $seg['eritema'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Seguimiento - ISSSTE</title>
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

    <!-- Barra Lateral -->
    <?php include 'sidebar.php'; ?>

    <!-- Área Principal -->
    <main class="main-wrapper">

      <!-- Cabecera Superior -->
      <?php include 'header.php'; ?>

      <!-- Formulario de Modificación -->
      <section class="content-body">

        <!-- Botón VOLVER -->
        <div class="form-toolbar">
          <a href="tablas_seguimiento.php"
            class="btn-volver"
            style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
            VOLVER
          </a>
        </div>

        <!-- Tarjeta de Modificación -->
        <div class="form-register-card">

          <div class="form-register-header">
            <h2 class="form-register-title">
              Editar Seguimiento Clínico (SEG-<?= str_pad($idSeguimiento, 3, '0', STR_PAD_LEFT) ?>)
            </h2>
            <p class="form-register-subtitle">
              Modifique los parámetros clínicos del paciente.
            </p>
          </div>

          <!-- Formulario que apunta a actualizar_seguimiento.php -->
          <form action="actualizar_seguimiento.php" method="POST" class="patient-form">

            <input type="hidden" name="id_seguimiento" value="<?= $idSeguimiento ?>">

            <!-- Fila 1: Paciente y Cama -->
            <div class="form-row-2">
              <div class="form-field">
                <label for="regPaciente" class="form-label">Paciente</label>
                <input
                  type="text"
                  id="regPaciente"
                  name="paciente"
                  class="form-control"
                  value="<?= htmlspecialchars($paciente, ENT_QUOTES, 'UTF-8') ?>"
                  required>
              </div>
              <div class="form-field">
                <label for="regCama" class="form-label">Cama</label>
                <input
                  type="text"
                  id="regCama"
                  name="cama"
                  class="form-control"
                  value="<?= htmlspecialchars($cama, ENT_QUOTES, 'UTF-8') ?>"
                  required>
              </div>
            </div>

            <!-- Fila 2: Fecha y Dolor EVA -->
            <div class="form-row-2">
              <div class="form-field">
                <label for="regFecha" class="form-label">Fecha</label>
                <input
                  type="text"
                  id="regFecha"
                  name="fecha"
                  class="form-control"
                  value="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>"
                  required>
              </div>
              <div class="form-field">
                <label for="regDolor" class="form-label">Dolor (EVA)</label>
                <input
                  type="text"
                  id="regDolor"
                  name="dolor"
                  class="form-control"
                  value="<?= htmlspecialchars($dolor, ENT_QUOTES, 'UTF-8') ?>">
              </div>
            </div>

            <!-- Fila 3: Bordes y Exudados -->
            <div class="form-row-2">
              <div class="form-field">
                <label for="regBordes" class="form-label">Bordes</label>
                <input
                  type="text"
                  id="regBordes"
                  name="bordes"
                  class="form-control"
                  value="<?= htmlspecialchars($bordes, ENT_QUOTES, 'UTF-8') ?>">
              </div>
              <div class="form-field">
                <label for="regExudados" class="form-label">Exudados</label>
                <input
                  type="text"
                  id="regExudados"
                  name="exudados"
                  class="form-control"
                  value="<?= htmlspecialchars($exudados, ENT_QUOTES, 'UTF-8') ?>">
              </div>
            </div>

            <!-- Fila 4: Eritema (Textarea) -->
            <div class="form-field-full">
              <label for="regEritema" class="form-label">Eritema</label>
              <textarea
                id="regEritema"
                name="eritema"
                class="form-control form-textarea"
                rows="3"><?= htmlspecialchars($eritema, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <!-- Botón de actualización -->
            <div class="form-actions-right" style="margin-top: 20px; display: flex; justify-content: flex-end;">
              <button type="submit" class="btn-guardar">
                Actualizar Seguimiento
              </button>
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