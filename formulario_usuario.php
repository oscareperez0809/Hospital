<?php
session_start();
$paginaActiva = 'usuarios';
$tituloHeader = 'Registro de Usuarios';

// Valores por si se viene de editar
$cedula = $_GET['cedula'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$paterno = $_GET['paterno'] ?? '';
$materno = $_GET['materno'] ?? '';
$depto = $_GET['depto'] ?? '';
$area = $_GET['area'] ?? '';
$rol = $_GET['rol'] ?? 'empleado';
$estado = $_GET['estado'] ?? 'activo';
$esEdicion = !empty($cedula);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar Usuario' : 'Registro de Usuario' ?> - ISSSTE</title>
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

            <!-- Formulario de Usuario (Fiel al diseño institucional) -->
            <section class="content-body">
                
                <!-- Botón VOLVER rectangular gris -->
                <div class="form-toolbar">
                    <a href="tablas_usuarios.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <!-- Tarjeta Blanca de Registro de Usuario -->
                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title"><?= $esEdicion ? 'Editar Usuario' : 'Registro de Usuario' ?></h2>
                        <p class="form-register-subtitle">Complete los datos personales, laborales y permisos de acceso del usuario.</p>
                    </div>

                    <form action="tablas_usuarios.php" method="POST" class="patient-form" id="formUsuario">
                        
                        <!-- Fila 1: Cédula Profesional / No. Empleado y Nombre(s) -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="userCedula" class="form-label">Cédula Profesional / Número de Empleado</label>
                                <input 
                                    type="text" 
                                    id="userCedula" 
                                    name="cedula_empleado" 
                                    class="form-control" 
                                    placeholder="Ej. EMP-10492 o Céd. 1234567" 
                                    value="<?= htmlspecialchars($cedula) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="userNombre" class="form-label">Nombre(s)</label>
                                <input 
                                    type="text" 
                                    id="userNombre" 
                                    name="nombre" 
                                    class="form-control" 
                                    placeholder="Ingrese los nombres" 
                                    value="<?= htmlspecialchars($nombre) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 2: Apellido Paterno y Apellido Materno -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="userPaterno" class="form-label">Apellido Paterno</label>
                                <input 
                                    type="text" 
                                    id="userPaterno" 
                                    name="paterno" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido paterno" 
                                    value="<?= htmlspecialchars($paterno) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="userMaterno" class="form-label">Apellido Materno</label>
                                <input 
                                    type="text" 
                                    id="userMaterno" 
                                    name="materno" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido materno" 
                                    value="<?= htmlspecialchars($materno) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 3: Departamento y Área donde va a pertenecer -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="userDepto" class="form-label">Departamento</label>
                                <input 
                                    type="text" 
                                    id="userDepto" 
                                    name="departamento" 
                                    class="form-control" 
                                    placeholder="Ej. Urgencias, Medicina Interna, Cirugía" 
                                    value="<?= htmlspecialchars($depto) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="userArea" class="form-label">Área</label>
                                <input 
                                    type="text" 
                                    id="userArea" 
                                    name="area" 
                                    class="form-control" 
                                    placeholder="Ej. Triage, Quirófano 1, Hospitalización" 
                                    value="<?= htmlspecialchars($area) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 4: Contraseña y Estado -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="userPassword" class="form-label">Contraseña</label>
                                <div class="input-password-wrapper">
                                    <input 
                                        type="password" 
                                        id="userPassword" 
                                        name="password" 
                                        class="form-control" 
                                        placeholder="<?= $esEdicion ? 'Dejar en blanco para conservar actual' : 'Ingrese una contraseña segura' ?>" 
                                        <?= $esEdicion ? '' : 'required' ?>
                                    >
                                    <button type="button" id="toggleUserPassword" class="btn-eye-toggle" aria-label="Mostrar u ocultar contraseña">
                                        <svg id="eyeIconUser" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="userEstado" class="form-label">Estado de la Cuenta</label>
                                <select id="userEstado" name="estado" class="form-control" required>
                                    <option value="activo" <?= ($estado === 'activo') ? 'selected' : '' ?>>Activo (Permite inicio de sesión)</option>
                                    <option value="inactivo" <?= ($estado === 'inactivo') ? 'selected' : '' ?>>Inactivo (Acceso suspendido)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 5: Rol del Sistema (Exclusivo: solo una opción) -->
                        <div class="form-field-full" style="margin-top: 10px;">
                            <label class="form-label">Rol del Usuario (Selecciona únicamente una opción):</label>
                            
                            <div class="role-radio-group">
                                <!-- Opción 1: Empleado -->
                                <label class="role-radio-card">
                                    <input type="radio" name="rol" value="empleado" <?= ($rol === 'empleado') ? 'checked' : '' ?> required>
                                    <div class="role-card-content">
                                        <div class="role-card-header">
                                            <span class="role-card-title">🩺 Empleado</span>
                                            <span class="role-radio-dot"></span>
                                        </div>
                                        <p class="role-card-desc">Operativo. CRUD básico del hospital (Pacientes, Atención y Seguimiento).</p>
                                    </div>
                                </label>

                                <!-- Opción 2: Administrador -->
                                <label class="role-radio-card">
                                    <input type="radio" name="rol" value="admin" <?= ($rol === 'admin') ? 'checked' : '' ?>>
                                    <div class="role-card-content">
                                        <div class="role-card-header">
                                            <span class="role-card-title">🛡️ Administrador</span>
                                            <span class="role-radio-dot"></span>
                                        </div>
                                        <p class="role-card-desc">CRUD hospitalario y visualización del listado de empleados registrados.</p>
                                    </div>
                                </label>

                                <!-- Opción 3: Director General -->
                                <label class="role-radio-card">
                                    <input type="radio" name="rol" value="director" <?= ($rol === 'director') ? 'checked' : '' ?>>
                                    <div class="role-card-content">
                                        <div class="role-card-header">
                                            <span class="role-card-title">👑 Director General</span>
                                            <span class="role-radio-dot"></span>
                                        </div>
                                        <p class="role-card-desc">Acceso total. Puede registrar, editar, dar de baja y eliminar usuarios.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Fila Inferior: Botón Guardar -->
                        <div style="display: flex; justify-content: flex-end; margin-top: 26px;">
                            <button type="submit" class="btn-guardar">
                                <?= $esEdicion ? 'Actualizar Usuario' : 'Guardar Usuario' ?>
                            </button>
                        </div>

                    </form>
                </div>

            </section>
        </main>

    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
    <script>
        // Ojo para mostrar/ocultar contraseña
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggleUserPassword');
            const passInput = document.getElementById('userPassword');
            const eyeIcon = document.getElementById('eyeIconUser');

            const eyeOpen = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
            const eyeClosed = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;

            if (toggleBtn && passInput) {
                toggleBtn.addEventListener('click', () => {
                    const isPass = passInput.getAttribute('type') === 'password';
                    passInput.setAttribute('type', isPass ? 'text' : 'password');
                    eyeIcon.innerHTML = isPass ? eyeClosed : eyeOpen;
                });
            }
        });
    </script>
</body>
</html>
