<?php
session_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: tablas_seguimiento.php");
  exit();
}

$idSeguimiento = intval($_POST['id_seguimiento'] ?? 0);
$paciente = trim($_POST['paciente'] ?? '');
$cama = trim($_POST['cama'] ?? '');
$fecha = trim($_POST['fecha'] ?? '');
$dolor = trim($_POST['dolor'] ?? '');
$bordes = trim($_POST['bordes'] ?? '');
$exudados = trim($_POST['exudados'] ?? '');
$eritema = trim($_POST['eritema'] ?? '');

if (empty($conectar) || $idSeguimiento <= 0 || empty($paciente)) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

// Actualizar seguimiento clínico
$stmt = mysqli_prepare(
  $conectar,
  "UPDATE seguimiento SET 
        paciente = ?, 
        cama = ?, 
        fecha = ?, 
        dolor_eva = ?, 
        bordes = ?, 
        exudados = ?, 
        eritema = ? 
    WHERE id_seguimiento = ?"
);

if (!$stmt) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

mysqli_stmt_bind_param(
  $stmt,
  "sssssssi",
  $paciente,
  $cama,
  $fecha,
  $dolor,
  $bordes,
  $exudados,
  $eritema,
  $idSeguimiento
);

if (mysqli_stmt_execute($stmt)) {
  header("Location: tablas_seguimiento.php?actualizado=1");
  exit();
}

header("Location: tablas_seguimiento.php?error=1");
exit();
