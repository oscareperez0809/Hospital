<?php
session_start();
require_once "conexion.php";

$paginaActiva = 'paciente';
$tituloHeader = 'Control y Registro de Pacientes';

$mensajeExito = '';
$mensajeError = '';

if (isset($_GET['registrado'])) {
    $mensajeExito = 'Paciente registrado exitosamente.';
} elseif (isset($_GET['actualizado'])) {
    $mensajeExito = 'Paciente actualizado exitosamente.';
} elseif (isset($_GET['eliminado'])) {
    $mensajeExito = 'Paciente eliminado correctamente.';
} elseif (isset($_GET['error'])) {
    $mensajeError = 'No fue posible realizar la operación solicitada.';
}
?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - Sistema Hospitalario ISSSTE</title>

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

            <?php if (!empty($mensajeExito)): ?>
                <div style="background-color: rgba(34, 197, 94, 0.15); border: 1.5px solid #22c55e; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <span>✓</span> <?= htmlspecialchars($mensajeExito) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensajeError)): ?>
                <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <span>⚠</span> <?= htmlspecialchars($mensajeError) ?>
                </div>
            <?php endif; ?>

            <!-- Barra de herramientas -->
            <div class="patients-toolbar">

                <a href="formulario_paciente.php" class="btn-registrar" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                    REGISTRAR
                </a>

                <div class="search-pill-container">
                    <input
                        type="text"
                        id="searchPatients"
                        class="search-pill-input"
                        placeholder="BUSCAR"
                        autocomplete="off">

                    <span class="search-pill-icon" title="Buscar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                </div>

            </div>

            <!-- Tabla de pacientes -->
            <div class="patients-table-wrapper">

                <table class="patients-table" id="patientsTable">

                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Género</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $hayPacientesBD = false;

                        if (!empty($conectar)) {

                            $consultaPacientes = mysqli_query(
                                $conectar,
                                "SELECT * FROM pacientes ORDER BY cedula ASC"
                            );

                            if ($consultaPacientes && mysqli_num_rows($consultaPacientes) > 0) {

                                $hayPacientesBD = true;

                                while ($p = mysqli_fetch_assoc($consultaPacientes)) {

                                    $ced = htmlspecialchars($p['cedula']);
                                    $nom = htmlspecialchars($p['nombre']);
                                    $pat = htmlspecialchars($p['apellido_paterno'] ?? '');
                                    $mat = htmlspecialchars($p['apellido_materno'] ?? '');
                                    $gen = htmlspecialchars($p['genero']);
                        ?>

                                    <tr>

                                        <td class="cell-cedula"><?= $ced ?></td>

                                        <td><strong><?= $nom ?></strong></td>

                                        <td><?= $pat ?></td>

                                        <td><?= $mat ?></td>

                                        <td><?= $gen ?></td>

                                        <td class="cell-actions">

                                            <!-- Ver -->
                                            <a href="ver_paciente.php?cedula=<?= urlencode($p['cedula']) ?>"
                                                class="btn-tbl btn-tbl-view"
                                                style="text-decoration:none;"
                                                title="Ver expediente">
                                                Ver
                                            </a>

                                            <!-- Editar -->
                                            <a href="modificar_paciente.php?cedula=<?= urlencode($p['cedula']) ?>"
                                                class="btn-tbl btn-tbl-edit"
                                                style="text-decoration:none;"
                                                title="Editar paciente">
                                                Editar
                                            </a>

                                            <!-- Eliminar -->
                                            <form action="eliminar_paciente.php" method="POST" style="display:inline;">

                                                <input type="hidden" name="cedula" value="<?= $ced ?>">

                                                <button type="submit"
                                                    class="btn-tbl btn-tbl-delete"
                                                    onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars($nom . ' ' . $pat) ?> con cédula <?= $ced ?>?');">
                                                    Eliminar
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                        <?php
                                }
                            }
                        }

                        if (!$hayPacientesBD):
                        ?>

                            <tr>
                                <td colspan="6" style="text-align:center; padding: 48px 20px; color: var(--text-secondary);">

                                    <div style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--text-primary);">
                                        No hay pacientes registrados en la base de datos
                                    </div>

                                    <p style="font-size: 13.5px;">
                                        Haga clic en el botón <strong>REGISTRAR</strong> para agregar al primer paciente a la base de datos.
                                    </p>

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script src="dashboard.js"></script>

</body>
</html>