<?php
session_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: tablas_seguimiento.php");
  exit();
}

$idSeguimiento = intval($_POST['id_seguimiento'] ?? 0);
$idAtencion = intval($_POST['id_atencion'] ?? 0);
$fecha = trim($_POST['fecha'] ?? date('Y-m-d H:i:s'));

// Manejar conversión segura a NULL para llaves foráneas opcionales
$idBorde = !empty($_POST['id_borde']) ? intval($_POST['id_borde']) : null;
$idExudado = !empty($_POST['id_exudado']) ? intval($_POST['id_exudado']) : null;
$idDolor = !empty($_POST['id_dolor']) ? intval($_POST['id_dolor']) : null;
$idEritema = !empty($_POST['id_eritema']) ? intval($_POST['id_eritema']) : null;

$observaciones = trim($_POST['observaciones'] ?? '');

if (empty($conectar) || $idSeguimiento <= 0 || $idAtencion <= 0) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

$stmt = mysqli_prepare(
  $conectar,
  "UPDATE seguimiento SET 
        id_atencion = ?, 
        fecha = ?, 
        id_borde = ?, 
        id_exudado = ?, 
        id_dolor = ?, 
        id_eritema = ?, 
        observaciones = ? 
    WHERE id_seguimiento = ?"
);

if (!$stmt) {
  header("Location: tablas_seguimiento.php?error=1");
  exit();
}

mysqli_stmt_bind_param(
  $stmt,
  "isiiiisi",
  $idAtencion,
  $fecha,
  $idBorde,
  $idExudado,
  $idDolor,
  $idEritema,
  $observaciones,
  $idSeguimiento
);

if (mysqli_stmt_execute($stmt)) {
  header("Location: tablas_seguimiento.php?actualizado=1");
  exit();
}

header("Location: tablas_seguimiento.php?error=1");
exit();
