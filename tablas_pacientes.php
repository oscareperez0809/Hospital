<?php
session_start();
$paginaActiva = 'paciente';
$tituloHeader = 'Control y Registro de Pacientes';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - Sistema Hospitalario ISSSTE</title>
    <!-- Fuentes Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <!-- Script Anti-Parpadeo Inmediato de Modo Oscuro -->
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

        <!-- Barra Lateral con PACIENTE activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">

            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Pacientes -->
            <section class="content-body">

                <!-- Barra de herramientas: REGISTRAR y BUSCAR -->
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

                <!-- Tabla de Pacientes -->
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
                            <tr>
                                <td class="cell-cedula">12-4567-8910</td>
                                <td>María</td>
                                <td>Hernández</td>
                                <td>López</td>
                                <td>Femenino</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_paciente.php?cedula=12-4567-8910&nombre=María&paterno=Hernández&materno=López&genero=Femenino" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">14-2034-5567</td>
                                <td>Carlos</td>
                                <td>Ramírez</td>
                                <td>García</td>
                                <td>Masculino</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_paciente.php?cedula=14-2034-5567&nombre=Carlos&paterno=Ramírez&materno=García&genero=Masculino" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">11-9876-5432</td>
                                <td>Lucía</td>
                                <td>Torres</td>
                                <td>Martínez</td>
                                <td>Femenino</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_paciente.php?cedula=11-9876-5432&nombre=Lucía&paterno=Torres&materno=Martínez&genero=Femenino" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">13-7789-1122</td>
                                <td>Juan</td>
                                <td>Sánchez</td>
                                <td>Díaz</td>
                                <td>Masculino</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_paciente.php?cedula=13-7789-1122&nombre=Juan&paterno=Sánchez&materno=Díaz&genero=Masculino" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">15-3321-9988</td>
                                <td>Sofía</td>
                                <td>Gómez</td>
                                <td>Ruiz</td>
                                <td>Femenino</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_paciente.php?cedula=15-3321-9988&nombre=Sofía&paterno=Gómez&materno=Ruiz&genero=Femenino" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>

    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
</body>

</html>