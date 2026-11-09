<?php
session_start();
require_once "conexion.php";

// Seguridad: Verificar sesión (empleado no puede ver esta sección)
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario === 'empleado') {
  header("Location: dashboard.php");
  exit();
}

$paginaActiva = 'usuarios';
$tituloHeader = 'Expediente del Usuario';

$cedula = $_GET['cedula'] ?? '';
$u = null;

if (!empty($cedula) && !empty($conectar)) {
  $stmt = mysqli_prepare($conectar, "SELECT * FROM usuarios WHERE cedula = ? LIMIT 1");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $cedula);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res && $row = mysqli_fetch_assoc($res)) {
      $u = $row;
    }
  }
}

// Si no se encuentra el usuario, redirigir a la tabla
if (!$u) {
  header("Location: tablas_usuarios.php");
  exit();
}

$nombre = htmlspecialchars($u['nombre'] ?? '');
$paterno = htmlspecialchars($u['ape_pat'] ?? '');
$materno = htmlspecialchars($u['ape_mat'] ?? '');
$cedulaVal = htmlspecialchars($u['cedula'] ?? '');
$depto = htmlspecialchars($u['departamento'] ?? 'No asignado');
$area = htmlspecialchars($u['area'] ?? 'No asignada');
$rolVal = $u['rol'] ?? 'empleado';

// Formatear rol legible
$rolText = 'Empleado';
if ($rolVal === 'admin') $rolText = 'Administrador (Solo lectura)';
if ($rolVal === 'director') $rolText = 'Director General (Control Total)';

// Inicial para el avatar
$inicial = !empty($nombre) ? mb_strtoupper(mb_substr($nombre, 0, 1)) : 'U';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expediente de <?= $nombre ?> - ISSSTE</title>
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

    <!-- Barra Lateral con USUARIOS activo -->
    <?php include 'sidebar.php'; ?>

    <!-- Área Principal -->
    <main class="main-wrapper">

      <!-- Cabecera Superior -->
      <?php include 'header.php'; ?>

      <!-- Contenido de Vista Detallada -->
      <section class="content-body">

        <!-- Botón VOLVER -->
        <div class="form-toolbar">
          <a href="tablas_usuarios.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
            VOLVER A LA LISTA
          </a>
        </div>

        <!-- Tarjeta Principal del Expediente (Estilo unificado) -->
        <div class="form-register-card" style="max-width: 850px; margin: 0 auto;">

          <!-- Cabecera de la tarjeta con Avatar -->
          <div style="display: flex; align-items: center; gap: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 24px; margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary-color, #0284c7); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 700;">
              <?= $inicial ?>
            </div>
            <div>
              <h2 style="font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
                <?= "{$nombre} {$paterno} {$materno}" ?>
              </h2>
              <p style="font-size: 13.5px; color: var(--text-secondary);">
                Cédula / No. Empleado: <strong><?= $cedulaVal ?></strong>
              </p>
            </div>
          </div>

          <!-- Grilla de Información Detallada -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">

            <div style="background: var(--bg-main); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color);">
              <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 4px;">Nombre(s)</span>
              <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);"><?= $nombre ?></span>
            </div>

            <div style="background: var(--bg-main); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color);">
              <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 4px;">Apellidos</span>
              <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);"><?= "{$paterno} {$materno}" ?></span>
            </div>

            <div style="background: var(--bg-main); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color);">
              <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 4px;">Rol en el Sistema</span>
              <span style="font-size: 15px; font-weight: 600; color: #2563eb;"><?= $rolText ?></span>
            </div>

            <div style="background: var(--bg-main); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color);">
              <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 4px;">Departamento</span>
              <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);"><?= $depto ?></span>
            </div>

            <div style="background: var(--bg-main); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color); grid-column: span 2;">
              <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 4px;">Área Específica</span>
              <span style="font-size: 15px; font-weight: 600; color: var(--text-primary);"><?= $area ?></span>
            </div>

          </div>

          <!-- Acciones Inferiores (Si es director, mostrar acceso rápido a editar) -->
          <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="tablas_usuarios.php" class="btn-volver" style="text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600;">
              Regresar
            </a>
            <?php if ($rolUsuario === 'director'): ?>
              <a href="formulario_usuario.php?cedula=<?= urlencode($cedulaVal) ?>" class="btn-guardar" style="text-decoration: none; padding: 10px 24px; border-radius: 8px; font-weight: 700;">
                Editar Usuario
              </a>
            <?php endif; ?>
          </div>

        </div>

      </section>
    </main>

  </div>

  <!-- Scripts -->
  <script src="dashboard.js"></script>
</body>

</html>