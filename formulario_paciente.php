<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'paciente';
$tituloHeader = 'Registro de Pacientes';

// Valores por si se viene de editar
$cedula = $_GET['cedula'] ?? '';
$nombre = '';
$paterno = '';
$materno = '';
$nacimiento = '';
$genero = '';
$alergias = '';
$cirugia = '';

// Si viene la cédula y la BD está disponible, cargar los datos actuales del paciente
if (!empty($cedula) && !empty($conectar)) {
    $stmt = mysqli_prepare($conectar, "SELECT * FROM pacientes WHERE cedula = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $cedula);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res && $p = mysqli_fetch_assoc($res)) {
            $nombre = $p['nombre'] ?? '';
            $paterno = $p['apellido_paterno'] ?? ($p['paterno'] ?? '');
            $materno = $p['apellido_materno'] ?? ($p['materno'] ?? '');
            $nacimiento = !empty($p['fecha_nacimiento']) ? substr($p['fecha_nacimiento'], 0, 10) : '';
            $genero = $p['genero'] ?? '';
            $alergias = $p['alergias'] ?? '';
            $cirugia = !empty($p['fecha_cirugia']) ? substr($p['fecha_cirugia'], 0, 10) : '';
        }
    }
}
$esEdicion = !empty($cedula);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Paciente - ISSSTE</title>
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
        
        <!-- Barra Lateral con PACIENTE activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Formulario de Paciente (Fiel a la imagen) -->
            <section class="content-body">
                
                <!-- Botón VOLVER rectangular gris -->
                <div class="form-toolbar">
                    <a href="tablas_pacientes.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <!-- Tarjeta Blanca de Registro de Paciente -->
                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title"><?= $esEdicion ? 'Editar Paciente' : 'Registro de Paciente' ?></h2>
                        <p class="form-register-subtitle">Complete la información del paciente para continuar con el registro.</p>
                    </div>

                    <form action="tablas_pacientes.php" method="POST" class="patient-form">
                        
                        <?php if ($esEdicion): ?>
                            <!-- Campo oculto para conservar o actualizar la cédula original -->
                            <input type="hidden" name="cedula_original" value="<?= htmlspecialchars($cedula) ?>">
                        <?php endif; ?>

                        <!-- Fila 1: Cédula y Nombre(s) -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regCedula" class="form-label">Cédula</label>
                                <input 
                                    type="text" 
                                    id="regCedula" 
                                    name="cedula" 
                                    class="form-control" 
                                    placeholder="Ingrese la cédula (ej. 12-4567-8910)" 
                                    value="<?= htmlspecialchars($cedula) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="regNombre" class="form-label">Nombre(s)</label>
                                <input 
                                    type="text" 
                                    id="regNombre" 
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
                                <label for="regPaterno" class="form-label">Apellido Paterno</label>
                                <input 
                                    type="text" 
                                    id="regPaterno" 
                                    name="apellido_paterno" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido paterno" 
                                    value="<?= htmlspecialchars($paterno) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="regMaterno" class="form-label">Apellido Materno</label>
                                <input 
                                    type="text" 
                                    id="regMaterno" 
                                    name="apellido_materno" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido materno" 
                                    value="<?= htmlspecialchars($materno) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 3: Fecha de Nacimiento y Género -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="regNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <div class="input-with-icon">
                                    <input 
                                        type="date" 
                                        id="regNacimiento" 
                                        name="fecha_nacimiento" 
                                        class="form-control" 
                                        value="<?= htmlspecialchars($nacimiento) ?>"
                                    >
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="regGenero" class="form-label">Género</label>
                                <div class="select-with-icon">
                                    <select id="regGenero" name="genero" class="form-control" required>
                                        <option value="" disabled <?= empty($genero) ? 'selected' : '' ?>>Seleccione un género</option>
                                        <option value="Femenino" <?= ($genero === 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                                        <option value="Masculino" <?= ($genero === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Alergias (Textarea) -->
                        <div class="form-field-full">
                            <label for="regAlergias" class="form-label">Alergias</label>
                            <textarea 
                                id="regAlergias" 
                                name="alergias" 
                                class="form-control form-textarea" 
                                placeholder="Describa las alergias conocidas (ej. Penicilina, Ninguna)" 
                                rows="3"
                            ><?= htmlspecialchars($alergias) ?></textarea>
                        </div>

                        <!-- Fila 5: Fecha de Cirugía y Botón Guardar -->
                        <div class="form-row-bottom">
                            <div class="form-field field-cirugia">
                                <label for="regCirugia" class="form-label">Fecha de Cirugía</label>
                                <div class="input-with-icon">
                                    <input 
                                        type="date" 
                                        id="regCirugia" 
                                        name="fecha_cirugia" 
                                        class="form-control" 
                                        value="<?= htmlspecialchars($cirugia) ?>"
                                    >
                                </div>
                            </div>
                            <div class="form-actions-right">
                                <button type="submit" class="btn-guardar">
                                    <?= $esEdicion ? 'Actualizar Paciente' : 'Guardar Paciente' ?>
                                </button>
                            </div>
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
