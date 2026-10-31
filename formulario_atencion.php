<?php
session_start();
$paginaActiva = 'atencion';
$tituloHeader = 'Registro de Atención Médica';

$cedula = $_GET['cedula'] ?? '';
$depto = $_GET['depto'] ?? '';
$especialidad = $_GET['especialidad'] ?? '';
$medico = $_GET['medico'] ?? '';
$cama = $_GET['cama'] ?? '';
$esEdicion = !empty($cedula);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Atención - ISSSTE</title>
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
        
        <!-- Barra Lateral con ATENCIÓN activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Formulario Crear Atención (Fiel a la imagen) -->
            <section class="content-body">
                
                <!-- Botón VOLVER rectangular gris -->
                <div class="form-toolbar">
                    <a href="tablas_atencion.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <!-- Tarjeta Blanca de Crear Atención -->
                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title"><?= $esEdicion ? 'Editar Atención' : 'Crear Atención' ?></h2>
                        <p class="form-register-subtitle">Complete la información de atención obligatoria</p>
                    </div>

                    <form action="tablas_atencion.php" method="POST" class="patient-form">
                        
                        <!-- Fila 1: Cédula y departamento -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnCedula" class="form-label">Cédula</label>
                                <input 
                                    type="text" 
                                    id="atnCedula" 
                                    name="cedula" 
                                    class="form-control" 
                                    placeholder="Ingrese la cédula" 
                                    value="<?= htmlspecialchars($cedula) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="atnDepto" class="form-label">departamento</label>
                                <input 
                                    type="text" 
                                    id="atnDepto" 
                                    name="departamento" 
                                    class="form-control" 
                                    placeholder="Ingrese el departamento" 
                                    value="<?= htmlspecialchars($depto) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 2: Especialidad y Medico -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnEspecialidad" class="form-label">Especialidad</label>
                                <input 
                                    type="text" 
                                    id="atnEspecialidad" 
                                    name="especialidad" 
                                    class="form-control" 
                                    placeholder="Ingresa la especialidad" 
                                    value="<?= htmlspecialchars($especialidad) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="atnMedico" class="form-label">Medico</label>
                                <input 
                                    type="text" 
                                    id="atnMedico" 
                                    name="medico" 
                                    class="form-control" 
                                    placeholder="Ingrese el medico tratante" 
                                    value="<?= htmlspecialchars($medico) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 3: Fecha de Ingreso y Fecha de egreso -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="atnIngreso" class="form-label">Fecha de Ingreso</label>
                                <div class="input-with-icon">
                                    <input 
                                        type="date" 
                                        id="atnIngreso" 
                                        name="fecha_ingreso" 
                                        class="form-control" 
                                        placeholder="DD / MM / AAAA"
                                    >
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="atnEgreso" class="form-label">Fecha de egreso</label>
                                <div class="input-with-icon">
                                    <input 
                                        type="date" 
                                        id="atnEgreso" 
                                        name="fecha_egreso" 
                                        class="form-control" 
                                        placeholder="DD / MM / AAAA"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Padecimiento (Textarea) -->
                        <div class="form-field-full">
                            <label for="atnPadecimiento" class="form-label">Padecimiento</label>
                            <textarea 
                                id="atnPadecimiento" 
                                name="padecimiento" 
                                class="form-control form-textarea" 
                                placeholder="Describa el padecimietno" 
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Fila 5: cama, Piso, etiologia y Botón Guardar -->
                        <div class="form-row-atencion-bottom">
                            <div class="form-field">
                                <label for="atnCama" class="form-label">cama</label>
                                <input 
                                    type="text" 
                                    id="atnCama" 
                                    name="cama" 
                                    class="form-control" 
                                    placeholder="Ingrese la cama" 
                                    value="<?= htmlspecialchars($cama) ?>"
                                >
                            </div>
                            <div class="form-field">
                                <label for="atnPiso" class="form-label">Piso</label>
                                <input 
                                    type="text" 
                                    id="atnPiso" 
                                    name="piso" 
                                    class="form-control" 
                                    placeholder="Ingrese piso"
                                >
                            </div>
                            <div class="form-field">
                                <label for="atnEtiologia" class="form-label">etiologia</label>
                                <input 
                                    type="text" 
                                    id="atnEtiologia" 
                                    name="etiologia" 
                                    class="form-control" 
                                    placeholder="Ingrese piso"
                                >
                            </div>
                            <div class="form-actions-btn">
                                <button type="submit" class="btn-guardar">
                                    Guardar
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
