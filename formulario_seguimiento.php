<?php
session_start();
$paginaActiva = 'seguimiento';
$tituloHeader = 'Registro de Seguimiento Clínico';

$paciente = $_GET['paciente'] ?? '';
$dolor = $_GET['dolor'] ?? '';
$eritema = $_GET['eritema'] ?? '';
$borde = $_GET['borde'] ?? '';
$exudado = $_GET['exudado'] ?? '';
$esEdicion = !empty($paciente);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Seguimiento - ISSSTE</title>
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
        
        <!-- Barra Lateral con SEGUIMIENTO activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Formulario Crear Seguimiento (Fiel a la imagen) -->
            <section class="content-body">
                
                <!-- Botón VOLVER rectangular gris -->
                <div class="form-toolbar">
                    <a href="tablas_seguimiento.php" class="btn-volver" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                        VOLVER
                    </a>
                </div>

                <!-- Tarjeta Blanca de Crear Seguimiento -->
                <div class="form-register-card">
                    <div class="form-register-header">
                        <h2 class="form-register-title"><?= $esEdicion ? 'Editar Seguimiento' : 'Crear Seguimiento' ?></h2>
                        <p class="form-register-subtitle">Complete la información del paciente para continuar con el registro.</p>
                    </div>

                    <form action="tablas_seguimiento.php" method="POST" class="patient-form">
                        
                        <!-- Fila 1: Paciente / cama y dolor -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="segPaciente" class="form-label">Paciente / cama</label>
                                <input 
                                    type="text" 
                                    id="segPaciente" 
                                    name="paciente_cama" 
                                    class="form-control" 
                                    placeholder="Ingrese la atención" 
                                    value="<?= htmlspecialchars($paciente) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="segDolor" class="form-label">dolor</label>
                                <input 
                                    type="text" 
                                    id="segDolor" 
                                    name="dolor" 
                                    class="form-control" 
                                    placeholder="Ingrese los nombres" 
                                    value="<?= htmlspecialchars($dolor) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 2: fecha y eritema -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="segFecha" class="form-label">fecha</label>
                                <div class="input-with-icon">
                                    <input 
                                        type="date" 
                                        id="segFecha" 
                                        name="fecha" 
                                        class="form-control" 
                                        placeholder="DD / MM / AAAA"
                                    >
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="segEritema" class="form-label">eritema</label>
                                <div class="select-with-icon">
                                    <select id="segEritema" name="eritema" class="form-control" required>
                                        <option value="" disabled <?= empty($eritema) ? 'selected' : '' ?>>Seleccione un género</option>
                                        <option value="No presenta" <?= ($eritema === 'No presenta') ? 'selected' : '' ?>>No presenta</option>
                                        <option value="Leve" <?= ($eritema === 'Leve') ? 'selected' : '' ?>>Leve</option>
                                        <option value="Moderado" <?= ($eritema === 'Moderado') ? 'selected' : '' ?>>Moderado</option>
                                        <option value="Severo" <?= ($eritema === 'Severo') ? 'selected' : '' ?>>Severo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: borde y exudado -->
                        <div class="form-row-2">
                            <div class="form-field">
                                <label for="segBorde" class="form-label">borde</label>
                                <input 
                                    type="text" 
                                    id="segBorde" 
                                    name="borde" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido paterno" 
                                    value="<?= htmlspecialchars($borde) ?>"
                                    required
                                >
                            </div>
                            <div class="form-field">
                                <label for="segExudado" class="form-label">exudado</label>
                                <input 
                                    type="text" 
                                    id="segExudado" 
                                    name="exudado" 
                                    class="form-control" 
                                    placeholder="Ingrese el apellido materno" 
                                    value="<?= htmlspecialchars($exudado) ?>"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Fila 4: observaciones (Textarea) -->
                        <div class="form-field-full">
                            <label for="segObservaciones" class="form-label">observaciones</label>
                            <textarea 
                                id="segObservaciones" 
                                name="observaciones" 
                                class="form-control form-textarea" 
                                placeholder="Describa observaciones" 
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Fila 5: Botón Guardar centrado / alineado -->
                        <div style="display: flex; justify-content: center; margin-top: 18px;">
                            <button type="submit" class="btn-guardar">
                                Guardar
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
