<?php
session_start();
require_once "conexion.php";

$paginaActiva = 'paciente';
$tituloHeader = 'Modificar Paciente';

// Recibir cédula del paciente
$cedula = $_GET['cedula'] ?? '';

if (empty($cedula)) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

// Inicializar variables
$nombre = '';
$paterno = '';
$materno = '';
$nacimiento = '';
$genero = '';
$alergias = '';
$cirugia = '';

// Consultar paciente
$stmt = mysqli_prepare($conectar, "SELECT * FROM pacientes WHERE cedula = ? LIMIT 1");

if (!$stmt) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $cedula);
mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);
$paciente = mysqli_fetch_assoc($res);

mysqli_stmt_close($stmt);

// Verificar que exista el paciente
if (!$paciente) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

// Cargar información
$nombre = $paciente['nombre'] ?? '';
$paterno = $paciente['apellido_paterno'] ?? '';
$materno = $paciente['apellido_materno'] ?? '';
$nacimiento = !empty($paciente['fecha_nacimiento'])
    ? substr($paciente['fecha_nacimiento'], 0, 10)
    : '';
$genero = $paciente['genero'] ?? '';
$alergias = $paciente['alergias'] ?? '';
$cirugia = !empty($paciente['fecha_cirugia'])
    ? substr($paciente['fecha_cirugia'], 0, 10)
    : '';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modificar Paciente - ISSSTE</title>

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

                    <a href="tablas_pacientes.php"
                        class="btn-volver"
                        style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">

                        VOLVER

                    </a>

                </div>

                <!-- Tarjeta de Modificación -->
                <div class="form-register-card">

                    <div class="form-register-header">

                        <h2 class="form-register-title">
                            Editar Paciente
                        </h2>

                        <p class="form-register-subtitle">
                            Modifique la información del paciente para actualizar su registro.
                        </p>

                    </div>

                    <!-- Formulario -->
                    <form action="actualizar_paciente.php" method="POST" class="patient-form">

                        <!-- Cédula original -->
                        <input
                            type="hidden"
                            name="cedula_original"
                            value="<?= htmlspecialchars($cedula, ENT_QUOTES, 'UTF-8') ?>"
                        >

                        <!-- Fila 1: Cédula y Nombre -->
                        <div class="form-row-2">

                            <div class="form-field">

                                <label for="regCedula" class="form-label">
                                    Cédula
                                </label>

                                <input
                                    type="text"
                                    id="regCedula"
                                    name="cedula"
                                    class="form-control"
                                    placeholder="Ingrese la cédula"
                                    value="<?= htmlspecialchars($cedula, ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >

                            </div>

                            <div class="form-field">

                                <label for="regNombre" class="form-label">
                                    Nombre(s)
                                </label>

                                <input
                                    type="text"
                                    id="regNombre"
                                    name="nombre"
                                    class="form-control"
                                    placeholder="Ingrese los nombres"
                                    value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >

                            </div>

                        </div>

                        <!-- Fila 2: Apellidos -->
                        <div class="form-row-2">

                            <div class="form-field">

                                <label for="regPaterno" class="form-label">
                                    Apellido Paterno
                                </label>

                                <input
                                    type="text"
                                    id="regPaterno"
                                    name="apellido_paterno"
                                    class="form-control"
                                    placeholder="Ingrese el apellido paterno"
                                    value="<?= htmlspecialchars($paterno, ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >

                            </div>

                            <div class="form-field">

                                <label for="regMaterno" class="form-label">
                                    Apellido Materno
                                </label>

                                <input
                                    type="text"
                                    id="regMaterno"
                                    name="apellido_materno"
                                    class="form-control"
                                    placeholder="Ingrese el apellido materno"
                                    value="<?= htmlspecialchars($materno, ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >

                            </div>

                        </div>

                        <!-- Fila 3: Nacimiento y Género -->
                        <div class="form-row-2">

                            <div class="form-field">

                                <label for="regNacimiento" class="form-label">
                                    Fecha de Nacimiento
                                </label>

                                <div class="input-with-icon">

                                    <input
                                        type="date"
                                        id="regNacimiento"
                                        name="fecha_nacimiento"
                                        class="form-control"
                                        value="<?= htmlspecialchars($nacimiento, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>

                            </div>

                            <div class="form-field">

                                <label for="regGenero" class="form-label">
                                    Género
                                </label>

                                <div class="select-with-icon">

                                    <select id="regGenero" name="genero" class="form-control" required>

                                        <option value="" disabled <?= empty($genero) ? 'selected' : '' ?>>
                                            Seleccione un género
                                        </option>

                                        <option value="Femenino" <?= ($genero === 'Femenino') ? 'selected' : '' ?>>
                                            Femenino
                                        </option>

                                        <option value="Masculino" <?= ($genero === 'Masculino') ? 'selected' : '' ?>>
                                            Masculino
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <!-- Fila 4: Alergias -->
                        <div class="form-field-full">

                            <label for="regAlergias" class="form-label">
                                Alergias
                            </label>

                            <textarea
                                id="regAlergias"
                                name="alergias"
                                class="form-control form-textarea"
                                placeholder="Describa las alergias conocidas"
                                rows="3"
                            ><?= htmlspecialchars($alergias, ENT_QUOTES, 'UTF-8') ?></textarea>

                        </div>

                        <!-- Fila 5: Cirugía y botón -->
                        <div class="form-row-bottom">

                            <div class="form-field field-cirugia">

                                <label for="regCirugia" class="form-label">
                                    Fecha de Cirugía
                                </label>

                                <div class="input-with-icon">

                                    <input
                                        type="date"
                                        id="regCirugia"
                                        name="fecha_cirugia"
                                        class="form-control"
                                        value="<?= htmlspecialchars($cirugia, ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                </div>

                            </div>

                            <div class="form-actions-right">

                                <button type="submit" class="btn-guardar">
                                    Actualizar Paciente
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