<?php
session_start();
require_once "conexion.php";

// Seguridad: Solo el Director General puede eliminar usuarios
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario !== 'director') {
  header("Location: tablas_usuarios.php");
  exit();
}

$cedulaDel = trim($_GET['cedula'] ?? '');

if (empty($conectar) || empty($cedulaDel)) {
  header("Location: tablas_usuarios.php?error=1");
  exit();
}

// Ejecutar la eliminación de forma segura con consulta preparada
$stmt = mysqli_prepare($conectar, "DELETE FROM usuarios WHERE cedula = ?");

if (!$stmt) {
  header("Location: tablas_usuarios.php?error=1");
  exit();
}

mysqli_stmt_bind_param($stmt, "s", $cedulaDel);

if (mysqli_stmt_execute($stmt)) {
  header("Location: tablas_usuarios.php?eliminado=1");
  exit();
}

header("Location: tablas_usuarios.php?error=1");
exit();
