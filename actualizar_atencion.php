<?php
session_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: tablas_atencion.php");
  exit();
}

$idAtencion = intval($_POST['id_atencion'] ?? 0);
$cedula = trim($_POST['cedula'] ?? '');
$idDepartamento = intval($_POST['id_departamento'] ?? 0);
$idEspecialidad = intval($_POST['id_especialidad'] ?? 0);
$idEtiologia = intval($_POST['id_etiologia'] ?? 0);
$fechaIngreso = trim($_POST['fecha_ingreso'] ?? '');
$fechaEgreso = trim($_POST['fecha_egreso'] ?? '');
$cama = !empty($_POST['cama']) ? intval($_POST['cama']) : null;
$piso = !empty($_POST['piso']) ? intval($_POST['piso']) : null;
$padecimiento = trim($_POST['padecimiento'] ?? '');

$fechaIngresoVal = !empty($fechaIngreso) ? $fechaIngreso : date('Y-m-d H:i:s');
$fechaEgresoVal = !empty($fechaEgreso) ? $fechaEgreso : null;

if (empty($conectar) || $idAtencion <= 0 || empty($cedula)) {
  header("Location: tablas_atencion.php?error=1");
  exit();
}

// Actualizar atención médica
$stmt = mysqli_prepare(
  $conectar,
  "UPDATE atencion SET 
        cedula = ?, 
        id_departamento = ?, 
        id_especialidad = ?, 
        id_etiologia = ?, 
        fecha_ingreso = ?, 
        fecha_egreso = ?, 
        cama = ?, 
        piso = ?, 
        padecimiento = ? 
    WHERE id_atencion = ?"
);

if (!$stmt) {
  header("Location: tablas_atencion.php?error=1");
  exit();
}

mysqli_stmt_bind_param(
  $stmt,
  "siiississii",
  $cedula,
  $idDepartamento,
  $idEspecialidad,
  $idEtiologia,
  $fechaIngresoVal,
  $fechaEgresoVal,
  $cama,
  $piso,
  $padecimiento,
  $idAtencion
);

if (mysqli_stmt_execute($stmt)) {
  header("Location: tablas_atencion.php?actualizado=1");
  exit();
}

header("Location: tablas_atencion.php?error=1");
exit();
