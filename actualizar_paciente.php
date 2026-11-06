<?php
session_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tablas_pacientes.php");
    exit();
}

$cedula = trim($_POST['cedula'] ?? '');
$cedulaOriginal = trim($_POST['cedula_original'] ?? '');

$nombre = trim($_POST['nombre'] ?? '');
$paterno = trim($_POST['apellido_paterno'] ?? ($_POST['paterno'] ?? ''));
$materno = trim($_POST['apellido_materno'] ?? ($_POST['materno'] ?? ''));

$nacimiento = trim($_POST['fecha_nacimiento'] ?? ($_POST['nacimiento'] ?? ''));
$genero = trim($_POST['genero'] ?? '');
$alergias = trim($_POST['alergias'] ?? '');

$cirugia = trim($_POST['fecha_cirugia'] ?? ($_POST['cirugia'] ?? ''));

$nacimientoVal = !empty($nacimiento) ? $nacimiento : null;
$cirugiaVal = !empty($cirugia) ? $cirugia : null;

if (empty($conectar) || empty($cedula) || empty($cedulaOriginal)) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

// Actualizar paciente
$stmt = mysqli_prepare(
    $conectar,
    "UPDATE pacientes SET
        cedula = ?,
        nombre = ?,
        apellido_paterno = ?,
        apellido_materno = ?,
        fecha_nacimiento = ?,
        genero = ?,
        alergias = ?,
        fecha_cirugia = ?
    WHERE cedula = ?"
);

if (!$stmt) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "sssssssss",
    $cedula,
    $nombre,
    $paterno,
    $materno,
    $nacimientoVal,
    $genero,
    $alergias,
    $cirugiaVal,
    $cedulaOriginal
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: tablas_pacientes.php?actualizado=1");
    exit();
}

header("Location: tablas_pacientes.php?error=1");
exit();
?>