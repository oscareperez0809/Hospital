<?php
session_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tablas_pacientes.php");
    exit();
}

$cedula = trim($_POST['cedula'] ?? '');

if (empty($cedula) || empty($conectar)) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

$stmt = mysqli_prepare(
    $conectar,
    "DELETE FROM pacientes WHERE cedula = ?"
);

if (!$stmt) {
    header("Location: tablas_pacientes.php?error=1");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $cedula);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: tablas_pacientes.php?eliminado=1");
    exit();
}

header("Location: tablas_pacientes.php?error=1");
exit();
