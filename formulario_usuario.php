<?php
session_start();
require_once "conexion.php";

// Seguridad: Solo el Director General puede registrar usuarios
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario !== 'director') {
    header("Location: tablas_usuarios.php");
    exit();
}

$paginaActiva = 'usuarios';
$tituloHeader = 'Registro de Nuevo Usuario';

$mensajeErrorForm = '';

// Procesar el registro directamente en este mismo formulario (como tus otros módulos)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($conectar)) {
    $cedula   = trim($_POST['cedula'] ?? '');
    $nombre   = trim($_POST['nombre'] ?? '');
    $paterno  = trim($_POST['paterno'] ?? '');
    $materno  = trim($_POST['materno'] ?? '');
    $depto    = trim($_POST['departamento'] ?? '');
    $area     = trim($_POST['area'] ?? '');
    $rol      = trim($_POST['rol'] ?? 'empleado');
    $password = trim($_POST['contrasenia'] ?? '');

    if (!empty($cedula) && !empty($nombre) && !empty($paterno)) {
        // Verificar si la cédula ya existe
        $check = mysqli_prepare($conectar, "SELECT cedula FROM usuarios WHERE cedula = ?");
        if ($check) {
            mysqli_stmt_bind_param($check, "s", $cedula);
            mysqli_stmt_execute($check);
            $resCheck = mysqli_stmt_get_result($check);

            if ($resCheck && mysqli_num_rows($resCheck) > 0) {
                $mensajeErrorForm = 'La cédula o número de empleado ya se encuentra registrada en el sistema.';
            } else {
                // Encriptar contraseña (o usar 12345678 por defecto si se omite)
                $hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : password_hash('12345678', PASSWORD_DEFAULT);

                $stmt = mysqli_prepare(
                    $conectar,
                    "INSERT INTO usuarios (cedula, nombre, ape_pat, ape_mat, departamento, area, rol, contrasenia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssssssss", $cedula, $nombre, $paterno, $materno, $depto, $area, $rol, $hash);
                    if (mysqli_stmt_execute($stmt)) {
                        header("Location: tablas_usuarios.php?registrado=1");
                        exit();
                    }
                }
                $mensajeErrorForm = 'Error al guardar el usuario en la base de datos.';
            }
        }
    } else {
        $mensajeErrorForm = 'Por favor, complete los campos obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - Sistema ISSSTE</title>
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

                <?php if (!empty($mensajeErrorForm)): ?>
                    <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <span>⚠</span> <?= htmlspecialchars($mensajeErrorForm) ?>
                    </div>
                <?php endif; ?>

                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title">Registro de Nuevo Usuario</h2>
                        <p class="form-register-subtitle">Complete la información del personal para dar de alta su cuenta en el sistema.</p>
                    </div>

                    <!-- El action apunta a sí mismo (formulario_usuario.php) -->
                    <form action="formulario_usuario.php" method="POST" class="patient-form">

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regCedula" class="form-label">Cédula / No. Empleado</label>
                                <input type="text" id="regCedula" name="cedula" class="form-control" placeholder="Ej. EMP-10293" required>
                            </div>
                            <div class="form-field">
                                <label for="regRol" class="form-label">Rol en el Sistema</label>
                                <div class="select-with-icon">
                                    <select id="regRol" name="rol" class="form-control" required>
                                        <option value="empleado" selected>Empleado (Operativo)</option>
                                        <option value="admin">Administrador (Solo lectura)</option>
                                        <option value="director">Director General (Control Total)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regNombre" class="form-label">Nombre(s)</label>
                                <input type="text" id="regNombre" name="nombre" class="form-control" placeholder="Ingrese los nombres" required>
                            </div>
                            <div class="form-field">
                                <label for="regPaterno" class="form-label">Apellido Paterno</label>
                                <input type="text" id="regPaterno" name="paterno" class="form-control" placeholder="Ingrese el apellido paterno" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regMaterno" class="form-label">Apellido Materno</label>
                                <input type="text" id="regMaterno" name="materno" class="form-control" placeholder="Ingrese el apellido materno">
                            </div>
                            <div class="form-field">
                                <label for="regDepto" class="form-label">Departamento</label>
                                <input type="text" id="regDepto" name="departamento" class="form-control" placeholder="Ej. Urgencias, Consulta Externa">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regArea" class="form-label">Área Específica</label>
                                <input type="text" id="regArea" name="area" class="form-control" placeholder="Ej. Área A, Quirófano 2">
                            </div>
                            <div class="form-field">
                                <label for="regContrasenia" class="form-label">Contraseña</label>
                                <input type="password" id="regContrasenia" name="contrasenia" class="form-control" placeholder="••••••••">
                                <small style="color: var(--text-secondary); font-size: 11px; margin-top: 4px; display: block;">Por defecto: <code>12345678</code> si se omite.</small>
                            </div>
                        </div>

                        <div class="form-row-bottom" style="margin-top: 20px;">
                            <div class="form-field" style="visibility: hidden;"></div>
                            <div class="form-actions-right">
                                <button type="submit" class="btn-guardar">
                                    Guardar Usuario
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