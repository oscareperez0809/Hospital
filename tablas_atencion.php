<?php
session_start();
$paginaActiva = 'atencion';
$tituloHeader = 'Control de Atención Médica';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención Médica - ISSSTE</title>
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

        <!-- Barra Lateral con ATENCIÓN activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">

            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Atención (Fiel a la imagen) -->
            <section class="content-body">

                <!-- Barra superior con REGISTRAR, filtros y BUSCAR -->
                <div class="table-toolbar-container">
                    <div class="table-toolbar-left">
                        <a href="formulario_atencion.php" class="btn-chip" style="font-weight: 700;">
                            REGISTRAR
                        </a>
                        <button type="button" class="btn-chip">Departamento</button>
                        <button type="button" class="btn-chip">Especialidad</button>
                        <button type="button" class="btn-chip">Etiologia</button>
                    </div>

                    <!-- Cápsula BUSCAR a la derecha -->
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

                <!-- Tabla de Atención Médica -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>ID Atención</th>
                                <th>Cédula</th>
                                <th>Departamento</th>
                                <th>Especialidad</th>
                                <th>Médico</th>
                                <th>Cama</th>
                                <th>Piso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cell-cedula">ATN-001</td>
                                <td>12-4567-8910</td>
                                <td>Cirugía General</td>
                                <td>Traumatología</td>
                                <td>Dr. García López</td>
                                <td>201</td>
                                <td>2</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_atencion.php?cedula=12-4567-8910&depto=Cirugía General&especialidad=Traumatología&medico=Dr. García López&cama=201" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">ATN-002</td>
                                <td>14-2034-5567</td>
                                <td>Medicina Interna</td>
                                <td>Cardiología</td>
                                <td>Dra. Martínez Ruiz</td>
                                <td>305</td>
                                <td>3</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_atencion.php?cedula=14-2034-5567&depto=Medicina Interna&especialidad=Cardiología&medico=Dra. Martínez Ruiz&cama=305" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">ATN-003</td>
                                <td>11-9876-5432</td>
                                <td>Urgencias</td>
                                <td>Pediatría</td>
                                <td>Dr. Hernández Díaz</td>
                                <td>102</td>
                                <td>1</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_atencion.php?cedula=11-9876-5432&depto=Urgencias&especialidad=Pediatría&medico=Dr. Hernández Díaz&cama=102" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">ATN-004</td>
                                <td>13-7789-1122</td>
                                <td>Ginecología</td>
                                <td>Obstetricia</td>
                                <td>Dra. Torres Vega</td>
                                <td>410</td>
                                <td>4</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_atencion.php?cedula=13-7789-1122&depto=Ginecología&especialidad=Obstetricia&medico=Dra. Torres Vega&cama=410" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Eliminar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">ATN-005</td>
                                <td>15-3321-9988</td>
                                <td>Oncología</td>
                                <td>Oncología Médica</td>
                                <td>Dr. Sánchez Mora</td>
                                <td>508</td>
                                <td>5</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_atencion.php?cedula=15-3321-9988&depto=Oncología&especialidad=Oncología Médica&medico=Dr. Sánchez Mora&cama=508" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
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