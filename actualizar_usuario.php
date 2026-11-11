<?php
session_start();
require_once "conexion.php";

// Seguridad: Solo el Director General puede actualizar usuarios
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario !== 'director' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: tablas_usuarios.php");
  exit();
}

$cedulaOriginal = trim($_POST['cedula_original'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$paterno = trim($_POST['paterno'] ?? '');
$materno = trim($_POST['materno'] ?? '');
$departamento = trim($_POST['departamento'] ?? '');
$area = trim($_POST['area'] ?? '');
$rol = trim($_POST['rol'] ?? 'empleado');
$password = trim($_POST['contrasenia'] ?? '');

if (empty($conectar) || empty($cedulaOriginal)) {
  header("Location: tablas_usuarios.php?error=1");
  exit();
}

// Verificar si se va a actualizar también la contraseña o solo los datos generales
if (!empty($password)) {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = mysqli_prepare(
    $conectar,
    "UPDATE usuarios SET 
            nombre = ?, 
            ape_pat = ?, 
            ape_mat = ?, 
            departamento = ?, 
            area = ?, 
            rol = ?, 
            contrasenia = ? 
        WHERE cedula = ?"
  );

  if (!$stmt) {
    header("Location: tablas_usuarios.php?error=1");
    exit();
  }

  mysqli_stmt_bind_param(
    $stmt,
    "ssssssss",
    $nombre,
    $paterno,
    $materno,
    $departamento,
    $area,
    $rol,
    $hash,
    $cedulaOriginal
  );
} else {
  $stmt = mysqli_prepare(
    $conectar,
    "UPDATE usuarios SET 
            nombre = ?, 
            ape_pat = ?, 
            ape_mat = ?, 
            departamento = ?, 
            area = ?, 
            rol = ? 
        WHERE cedula = ?"
  );

  if (!$stmt) {
    header("Location: tablas_usuarios.php?error=1");
    exit();
  }

  mysqli_stmt_bind_param(
    $stmt,
    "sssssss",
    $nombre,
    $paterno,
    $materno,
    $departamento,
    $area,
    $rol,
    $cedulaOriginal
  );
}

if (mysqli_stmt_execute($stmt)) {
  header("Location: tablas_usuarios.php?actualizado=1");
  exit();
}

header("Location: tablas_usuarios.php?error=1");
exit();
