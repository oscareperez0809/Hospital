<?php
session_start();
require_once "conexion.php";

$paginaActiva = 'paciente';
$tituloHeader = 'Expediente del Paciente';

$cedula = trim($_GET['cedula'] ?? '');

if (empty($cedula) || empty($conectar)) {
    header("Location: pacientes.php?error=1");
    exit();
}

$stmt = mysqli_prepare($conectar, "SELECT * FROM pacientes WHERE cedula = ?");

mysqli_stmt_bind_param($stmt, "s", $cedula);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (!$resultado || mysqli_num_rows($resultado) === 0) {
    header("Location: pacientes.php?error=1");
    exit();
}

$p = mysqli_fetch_assoc($resultado);

$nombreCompleto = trim(
    $p['nombre'] . ' ' .
    $p['apellido_paterno'] . ' ' .
    $p['apellido_materno']
);

function mostrarDato($valor) {
    return !empty($valor) ? htmlspecialchars($valor) : 'No registrado';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente del Paciente - ISSSTE</title>

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

            <div class="modal-card" style="max-width: 580px; margin: 0 auto;">

                <div class="modal-header">
                    <h3>Expediente del Paciente</h3>
                </div>

                <div class="user-detail-header-card">

                    <div class="user-detail-avatar">
                        <?= htmlspecialchars(strtoupper(substr($p['nombre'], 0, 1))) ?>
                    </div>

                    <div class="user-detail-title">
                        <h4><?= htmlspecialchars($nombreCompleto) ?></h4>
                        <p>Cédula: <?= htmlspecialchars($p['cedula']) ?></p>
                    </div>

                </div>

                <div class="user-detail-grid">

                    <div class="user-detail-item">
                        <div class="user-detail-label">Nombre(s)</div>
                        <div class="user-detail-val"><?= mostrarDato($p['nombre']) ?></div>
                    </div>

                    <div class="user-detail-item">
                        <div class="user-detail-label">Apellido Paterno</div>
                        <div class="user-detail-val"><?= mostrarDato($p['apellido_paterno']) ?></div>
                    </div>

                    <div class="user-detail-item">
                        <div class="user-detail-label">Apellido Materno</div>
                        <div class="user-detail-val"><?= mostrarDato($p['apellido_materno']) ?></div>
                    </div>

                    <div class="user-detail-item">
                        <div class="user-detail-label">Género</div>
                        <div class="user-detail-val"><?= mostrarDato($p['genero']) ?></div>
                    </div>

                    <div class="user-detail-item">
                        <div class="user-detail-label">Fecha de Nacimiento</div>
                        <div class="user-detail-val"><?= mostrarDato($p['fecha_nacimiento']) ?></div>
                    </div>

                    <div class="user-detail-item">
                        <div class="user-detail-label">Fecha de Cirugía</div>
                        <div class="user-detail-val"><?= mostrarDato($p['fecha_cirugia']) ?></div>
                    </div>

                    <div class="user-detail-item user-detail-full">
                        <div class="user-detail-label">Alergias Conocidas</div>
                        <div class="user-detail-val">
                            <?= mostrarDato($p['alergias']) ?>
                        </div>
                    </div>

                </div>

                <div class="modal-actions">

                    <a href="modificar_paciente.php?cedula=<?= urlencode($p['cedula']) ?>"
                        class="btn-tbl btn-tbl-edit"
                        style="text-decoration:none; padding: 10px 22px; font-size: 13.5px; border-radius: 8px;">
                        Editar Paciente
                    </a>

                    <a href="tablas_pacientes.php" class="btn-cancel" style="text-decoration:none;">
                        Regresar
                    </a>

                </div>

            </div>

        </section>

    </main>

</div>

<script src="dashboard.js"></script>

</body>
</html>