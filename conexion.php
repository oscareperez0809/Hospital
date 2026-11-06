<?php
$host = "localhost";
$user = "root";
$contrasena = "";
$bd = "issste";

// Conexión a la base de datos MySQL
$conectar = @mysqli_connect($host, $user, $contrasena, $bd);

if ($conectar) {
    // Habilitar caracteres en español (ñ, acentos)
    mysqli_set_charset($conectar, "utf8mb4");
} else {
    // Registrar error sin romper las redirecciones header() de PHP
    error_log("Error al conectar con la base de datos ISSSTE: " . mysqli_connect_error());
}
