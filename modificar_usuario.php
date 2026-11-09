<?php
session_start();
require_once "conexion.php";

// Seguridad: Solo el Director General puede editar
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario !== 'director') {
    header("Location: tablas_usuarios.php");
    exit();
}

$paginaActiva = 'usuarios';
$tituloHeader = 'Editar Usuario';

$cedulaGet = $_GET['cedula'] ?? '';
$u = null;

// Cargar datos actuales
if (!empty($cedulaGet) && !empty($conectar)) {
    $stmt = mysqli_prepare($conectar, "SELECT * FROM usuarios WHERE cedula = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $cedulaGet);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $u = $row;
    }
}

if (!$u) {
    header("Location: tablas_usuarios.php");
    exit();
}

// Procesar actualización (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($conectar)) {
    $nombre   = trim($_POST['nombre'] ?? '');
    $paterno  = trim($_POST['paterno'] ?? '');
    $materno  = trim($_POST['materno'] ?? '');
    $depto    = trim($_POST['departamento'] ?? '');
    $area     = trim($_POST['area'] ?? '');
    $rol      = trim($_POST['rol'] ?? 'empleado');
    $password = trim($_POST['contrasenia'] ?? '');

    if (!empty($nombre) && !empty($paterno)) {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conectar, "UPDATE usuarios SET nombre=?, ape_pat=?, ape_mat=?, departamento=?, area=?, rol=?, contrasenia=? WHERE cedula=?");
            mysqli_stmt_bind_param($stmt, "ssssssss", $nombre, $paterno, $materno, $depto, $area, $rol, $hash, $cedulaGet);
            mysqli_stmt_execute($stmt);
        } else {
            $stmt = mysqli_prepare($conectar, "UPDATE usuarios SET nombre=?, ape_pat=?, ape_mat=?, departamento=?, area=?, rol=? WHERE cedula=?");
            mysqli_stmt_bind_param($stmt, "sssssss", $nombre, $paterno, $materno, $depto, $area, $rol, $cedulaGet);
            mysqli_stmt_execute($stmt);
        }
        header("Location: tablas_usuarios.php?actualizado=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - ISSSTE</title>
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
                    <a href="tablas_usuarios.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title">Modificar Datos de Usuario</h2>
                        <p class="form-register-subtitle">Actualice la información del personal en el sistema.</p>
                    </div>

                    <form action="editar_usuario.php?cedula=<?= urlencode($cedulaGet) ?>" method="POST" class="patient-form">
                        
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Cédula / No. Empleado (Fijo)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($u['cedula']) ?>" readonly style="opacity: 0.7; cursor: not-allowed;">
                            </div>
                            <div class="form-field">
                                <label for="regRol" class="form-label">Rol en el Sistema</label>
                                <div class="select-with-icon">
                                    <select id="regRol" name="rol" class="form-control" required>
                                        <option value="empleado" <?= ($u['rol'] === 'empleado') ? 'selected' : '' ?>>Empleado (Operativo)</option>
                                        <option value="admin" <?= ($u['rol'] === 'admin') ? 'selected' : '' ?>>Administrador (Solo lectura)</option>
                                        <option value="director" <?= ($u['rol'] === 'director') ? 'selected' : '' ?>>Director General (Control Total)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regNombre" class="form-label">Nombre(s)</label>
                                <input type="text" id="regNombre" name="nombre" class="form-control" value="<?= htmlspecialchars($u['nombre']) ?>" required>
                            </div>
                            <div class="form-field">
                                <label for="regPaterno" class="form-label">Apellido Paterno</label>
                                <input type="text" id="regPaterno" name="paterno" class="form-control" value="<?= htmlspecialchars($u['ape_pat'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regMaterno" class="form-label">Apellido Materno</label>
                                <input type="text" id="regMaterno" name="materno" class="form-control" value="<?= htmlspecialchars($u['ape_mat'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="regDepto" class="form-label">Departamento</label>
                                <input type="text" id="regDepto" name="departamento" class="form-control" value="<?= htmlspecialchars($u['departamento'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regArea" class="form-label">Área Específica</label>
                                <input type="text" id="regArea" name="area" class="form-control" value="<?= htmlspecialchars($u['area'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="regContrasenia" class="form-label">Contraseña (Opcional)</label>
                                <input type="password" id="regContrasenia" name="contrasenia" class="form-control" placeholder="••••••••">
                                <small style="color: var(--text-secondary); font-size: 11px; margin-top: 4px; display: block;">Dejar en blanco para mantener la actual.</small>
                            </div>
                        </div>

                        <div class="form-row-bottom" style="margin-top: 20px;">
                            <div class="form-field" style="visibility: hidden;"></div>
                            <div class="form-actions-right">
                                <button type="submit" class="btn-guardar">Actualizar Usuario</button>
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