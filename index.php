<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ISSSTE</title>
    <!-- Fuente moderna Inter / Poppins / Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
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
    <?php
    session_start();
    $mensajeError = "";
    $mensajeExito = "";

    // Procesamiento del formulario en PHP
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $correo = filter_var(trim($_POST["correo"] ?? ""), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST["password"] ?? "");

        if (empty($correo) || empty($password)) {
            $mensajeError = "Por favor, completa todos los campos.";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensajeError = "Ingresa un correo electrónico válido.";
        } else {
            // Ejemplo de verificación de credenciales simulada
            // Aquí puedes conectar a tu base de datos MySQL / PostgreSQL
            $usuarioValido = "usuario@issste.gob.mx";
            $passwordValido = "12345678";

            if ($correo === $usuarioValido && $password === $passwordValido) {
                $_SESSION["usuario"] = $correo;
                header("Location: dashboard.php");
                exit;
            } else {
                $mensajeError = "Correo o contraseña incorrectos.";
            }
        }
    }
    ?>

    <!-- Botón de Modo Oscuro en la esquina superior derecha -->
    <header class="header-top">
        <button id="themeToggle" class="btn-theme" type="button" aria-label="Cambiar tema">
            <svg id="themeIcon" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            <span id="themeText">Modo oscuro</span>
        </button>
    </header>

    <!-- Contenedor Principal Centrado -->
    <main class="login-wrapper">
        <div class="login-card">
            
            <!-- Logotipo Institucional ISSSTE -->
            <div class="logo-container">
                <img src="logo-issste.png" alt="Logotipo ISSSTE" class="issste-logo">
            </div>

            <!-- Título y Subtítulo -->
            <div class="card-header">
                <h1 class="card-title">Iniciar sesión</h1>
                <p class="card-subtitle">Ingresa tus datos para continuar</p>
            </div>

            <!-- Alertas de estado PHP -->
            <?php if (!empty($mensajeError)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($mensajeError) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensajeExito)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($mensajeExito) ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Login -->
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="login-form" novalidate>
                
                <!-- Campo de Correo -->
                <div class="form-group">
                    <label for="correo" class="form-label">Correo</label>
                    <input 
                        type="email" 
                        id="correo" 
                        name="correo" 
                        class="form-input" 
                        placeholder="nombre@empresa.com"
                        value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                        required
                        autocomplete="email"
                    >
                </div>

                <!-- Campo de Contraseña con alternador de visibilidad -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" id="togglePassword" class="btn-eye-toggle" aria-label="Mostrar u ocultar contraseña">
                            <!-- Ícono de Ojo -->
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Enlace de Olvidé mi contraseña -->
                <div class="forgot-password-container">
                    <a href="#" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
                </div>

                <!-- Botón de Iniciar Sesión -->
                <button type="submit" class="btn-submit">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </main>

    <!-- Script para Modo Oscuro y Mostrar/Ocultar Contraseña -->
    <script src="script.js"></script>
</body>
</html>
