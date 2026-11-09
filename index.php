<?php
session_start();
// Intentar incluir la conexión (soporta tanto $conectar como $conexion o $conn)
@include_once "conexion.php";
if (!isset($conectar) && isset($conexion)) $conectar = $conexion;
if (!isset($conectar) && isset($conn)) $conectar = $conn;

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cedula = trim($_POST["cedula"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($cedula) || empty($password)) {
        $mensajeError = "Por favor, completa todos los campos.";
    } else {
        $loginExitoso = false;

        // 1. Validar contra la Base de Datos MySQL
        if (!empty($conectar)) {
            $stmt = mysqli_prepare($conectar, "SELECT * FROM usuarios WHERE cedula = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $cedula);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                
                if ($res && $userBD = mysqli_fetch_assoc($res)) {
                    $hashBD = $userBD['contrasenia'] ?? '';
                    
                    // Verificación flexible (hash de PHP o texto plano por respaldo)
                    if (password_verify($password, $hashBD) || $password === $hashBD) {
                        if (($userBD['estado'] ?? 'activo') === 'inactivo') {
                            $mensajeError = "Esta cuenta ha sido dada de baja.";
                        } else {
                            $loginExitoso = true;
                            $_SESSION["usuario"] = $userBD['nombre'] . ' ' . ($userBD['ape_pat'] ?? '');
                            $_SESSION["rol"] = $userBD['rol'] ?? 'empleado';
                            $_SESSION["cedula"] = $userBD['cedula'];
                        }
                    } else {
                        $mensajeError = "Contraseña incorrecta para la cédula: " . htmlspecialchars($cedula);
                    }
                } else {
                    $mensajeError = "No se encontró ningún usuario con la cédula: " . htmlspecialchars($cedula);
                }
            } else {
                $mensajeError = "Error en la consulta SQL: " . mysqli_error($conectar);
            }
        } else {
            $mensajeError = "Error crítico: No hay conexión a la Base de Datos.";
        }

        // 2. Fallback maestro por si acaso
        if (!$loginExitoso && $cedula === "EMP-001" && $password === "12345678") {
            $loginExitoso = true;
            $_SESSION["usuario"] = "Director General";
            $_SESSION["rol"] = "director";
            $_SESSION["cedula"] = $cedula;
            $mensajeError = "";
        }

        if ($loginExitoso) {
            header("Location: dashboard.php");
            exit;
        } elseif (empty($mensajeError)) {
            $mensajeError = "Cédula o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ISSSTE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="login-wrapper">
        <div class="login-card">
            <div class="logo-container">
                <img src="logo-issste.png" alt="Logotipo ISSSTE" class="issste-logo">
            </div>
            <div class="card-header">
                <h1 class="card-title">Iniciar sesión</h1>
                <p class="card-subtitle">Ingresa tus datos para continuar</p>
            </div>

            <?php if (!empty($mensajeError)): ?>
                <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
                    <?= htmlspecialchars($mensajeError) ?>
                </div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="login-form">
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula / No. Empleado</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej. 45645" value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-submit">Iniciar Sesión</button>
            </form>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>