<?php
session_start();
$paginaActiva = 'seguimiento';
$tituloHeader = 'Control de Seguimiento Clínico';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento Clínico - ISSSTE</title>
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
        
        <!-- Barra Lateral con SEGUIMIENTO activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Seguimiento (Fiel a la imagen) -->
            <section class="content-body">
                
                <!-- Barra superior unificada con REGISTRAR, filtros y BUSCAR -->
                <div class="table-toolbar-container">
                    <div class="table-toolbar-left">
                        <a href="formulario_seguimiento.php" class="btn-chip" style="font-weight: 700;">
                            REGISTRAR
                        </a>
                        <button type="button" class="btn-chip">HISTORIAL</button>
                        <button type="button" class="btn-chip">Bordes</button>
                        <button type="button" class="btn-chip">Exudados</button>
                        <button type="button" class="btn-chip">Dolor_Eva</button>
                        <button type="button" class="btn-chip">Eritema</button>
                    </div>

                    <!-- Cápsula BUSCAR a la derecha (Misma forma y posición que las demás tablas) -->
                    <div class="search-pill-container">
                        <input 
                            type="text" 
                            id="searchPatients" 
                            class="search-pill-input" 
                            placeholder="BUSCAR"
                            autocomplete="off"
                        >
                        <span class="search-pill-icon" title="Buscar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Seguimiento Clínico -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>ID SEG.</th>
                                <th>PACIENTE</th>
                                <th>CAMA</th>
                                <th>FECHA</th>
                                <th>DOLOR</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cell-cedula">SEG-001</td>
                                <td>Pérez Pérez, Juan</td>
                                <td>
                                    <span class="cama-icon-wrapper">
                                        <!-- Ícono estilizado de Cama de hospital -->
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path>
                                        </svg>
                                        205
                                    </span>
                                </td>
                                <td>01/02/26</td>
                                <td>3 EVA</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_seguimiento.php?paciente=Pérez Pérez, Juan&dolor=3 EVA" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Borrar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">SEG-002</td>
                                <td>García López, María</td>
                                <td>
                                    <span class="cama-icon-wrapper">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path>
                                        </svg>
                                        102
                                    </span>
                                </td>
                                <td>01/02/26</td>
                                <td>7 EVA</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_seguimiento.php?paciente=García López, María&dolor=7 EVA" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Borrar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">SEG-003</td>
                                <td>Ríos Cruz, Carlos</td>
                                <td>
                                    <span class="cama-icon-wrapper">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path>
                                        </svg>
                                        314
                                    </span>
                                </td>
                                <td>01/02/26</td>
                                <td>2 EVA</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_seguimiento.php?paciente=Ríos Cruz, Carlos&dolor=2 EVA" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Borrar</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-cedula">SEG-004</td>
                                <td>Sánchez M., Ana</td>
                                <td>
                                    <span class="cama-icon-wrapper">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"></path>
                                        </svg>
                                        201
                                    </span>
                                </td>
                                <td>01/02/26</td>
                                <td>10 EVA</td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view" type="button">Ver</button>
                                    <a href="formulario_seguimiento.php?paciente=Sánchez M., Ana&dolor=10 EVA" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                    <button class="btn-tbl btn-tbl-delete" type="button">Borrar</button>
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
