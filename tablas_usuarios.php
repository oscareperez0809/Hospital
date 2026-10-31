<?php
session_start();
$paginaActiva = 'usuarios';
$tituloHeader = 'Gestión y Control de Usuarios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Sistema Hospitalario ISSSTE</title>
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
        
        <!-- Barra Lateral con USUARIOS activo -->
        <?php include 'sidebar.php'; ?>

        <!-- Área Principal -->
        <main class="main-wrapper">
            
            <!-- Cabecera Superior -->
            <?php include 'header.php'; ?>

            <!-- Contenido de Tabla de Usuarios -->
            <section class="content-body">
                
                <!-- Barra de herramientas: REGISTRAR, filtros y BUSCAR -->
                <div class="filter-chips-wrapper">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                        
                        <!-- Botones superiores: REGISTRAR y filtros por Rol / Estado -->
                        <div class="filter-chips-row">
                            <a href="formulario_usuario.php" class="btn-chip" style="font-weight: 700;">
                                REGISTRAR
                            </a>
                            <button type="button" class="btn-chip btn-filter-user active" data-filter="todos">Todos</button>
                            <button type="button" class="btn-chip btn-filter-user" data-filter="empleado">Empleados</button>
                            <button type="button" class="btn-chip btn-filter-user" data-filter="admin">Administradores</button>
                            <button type="button" class="btn-chip btn-filter-user" data-filter="director">Director General</button>
                            <button type="button" class="btn-chip btn-filter-user" data-filter="activo">Activos</button>
                        </div>

                        <!-- Cápsula BUSCAR a la derecha -->
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
                </div>

                <!-- Tabla Visual de Usuarios (Datos Rápidos) -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>Cédula / No. Emp</th>
                                <th>Nombre Completo</th>
                                <th>Departamento</th>
                                <th>Área</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Usuario 1: Director General -->
                            <tr data-rol="director" data-estado="activo"
                                data-nombre="Alejandro"
                                data-paterno="Morales"
                                data-materno="Navarro"
                                data-cedula="EMP-10492"
                                data-depto="Dirección Médica"
                                data-area="Dirección General"
                                data-rol-text="Director General"
                                data-estado-text="Activo"
                                data-fecha="15/01/2024">
                                <td class="cell-cedula">EMP-10492</td>
                                <td><strong>Alejandro Morales Navarro</strong></td>
                                <td>Dirección Médica</td>
                                <td>Dirección General</td>
                                <td><span class="status-badge badge-rol-director">Director General</span></td>
                                <td><span class="status-badge badge-activo">Activo</span></td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view btn-view-user" type="button" title="Ver más información">Ver</button>
                                    <a href="formulario_usuario.php?cedula=EMP-10492&nombre=Alejandro&paterno=Morales&materno=Navarro&depto=Dirección Médica&area=Dirección General&rol=director&estado=activo" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar usuario">Editar</a>
                                    <button class="btn-tbl btn-tbl-baja btn-toggle-baja" type="button" title="Dar de baja / reactivar">Baja</button>
                                    <button class="btn-tbl btn-tbl-delete" type="button" title="Eliminar usuario">Borrar</button>
                                </td>
                            </tr>

                            <!-- Usuario 2: Admin -->
                            <tr data-rol="admin" data-estado="activo"
                                data-nombre="Claudia"
                                data-paterno="Mendoza"
                                data-materno="Estrada"
                                data-cedula="EMP-20831"
                                data-depto="Recursos Humanos"
                                data-area="Administración de Personal"
                                data-rol-text="Administrador"
                                data-estado-text="Activo"
                                data-fecha="02/03/2024">
                                <td class="cell-cedula">EMP-20831</td>
                                <td><strong>Claudia Mendoza Estrada</strong></td>
                                <td>Recursos Humanos</td>
                                <td>Administración de Personal</td>
                                <td><span class="status-badge badge-rol-admin">Administrador</span></td>
                                <td><span class="status-badge badge-activo">Activo</span></td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view btn-view-user" type="button" title="Ver más información">Ver</button>
                                    <a href="formulario_usuario.php?cedula=EMP-20831&nombre=Claudia&paterno=Mendoza&materno=Estrada&depto=Recursos Humanos&area=Administración de Personal&rol=admin&estado=activo" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar usuario">Editar</a>
                                    <button class="btn-tbl btn-tbl-baja btn-toggle-baja" type="button" title="Dar de baja / reactivar">Baja</button>
                                    <button class="btn-tbl btn-tbl-delete" type="button" title="Eliminar usuario">Borrar</button>
                                </td>
                            </tr>

                            <!-- Usuario 3: Empleado -->
                            <tr data-rol="empleado" data-estado="activo"
                                data-nombre="Roberto"
                                data-paterno="Méndez"
                                data-materno="Cruz"
                                data-cedula="EMP-30455"
                                data-depto="Urgencias"
                                data-area="Triage y Choque"
                                data-rol-text="Empleado"
                                data-estado-text="Activo"
                                data-fecha="10/05/2024">
                                <td class="cell-cedula">EMP-30455</td>
                                <td><strong>Roberto Méndez Cruz</strong></td>
                                <td>Urgencias</td>
                                <td>Triage y Choque</td>
                                <td><span class="status-badge badge-rol-empleado">Empleado</span></td>
                                <td><span class="status-badge badge-activo">Activo</span></td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view btn-view-user" type="button" title="Ver más información">Ver</button>
                                    <a href="formulario_usuario.php?cedula=EMP-30455&nombre=Roberto&paterno=Méndez&materno=Cruz&depto=Urgencias&area=Triage y Choque&rol=empleado&estado=activo" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar usuario">Editar</a>
                                    <button class="btn-tbl btn-tbl-baja btn-toggle-baja" type="button" title="Dar de baja / reactivar">Baja</button>
                                    <button class="btn-tbl btn-tbl-delete" type="button" title="Eliminar usuario">Borrar</button>
                                </td>
                            </tr>

                            <!-- Usuario 4: Empleado -->
                            <tr data-rol="empleado" data-estado="activo"
                                data-nombre="Carmen"
                                data-paterno="Salazar"
                                data-materno="Soto"
                                data-cedula="EMP-30812"
                                data-depto="Medicina Interna"
                                data-area="Hospitalización Piso 2"
                                data-rol-text="Empleado"
                                data-estado-text="Activo"
                                data-fecha="18/06/2024">
                                <td class="cell-cedula">EMP-30812</td>
                                <td><strong>Carmen Salazar Soto</strong></td>
                                <td>Medicina Interna</td>
                                <td>Hospitalización Piso 2</td>
                                <td><span class="status-badge badge-rol-empleado">Empleado</span></td>
                                <td><span class="status-badge badge-activo">Activo</span></td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view btn-view-user" type="button" title="Ver más información">Ver</button>
                                    <a href="formulario_usuario.php?cedula=EMP-30812&nombre=Carmen&paterno=Salazar&materno=Soto&depto=Medicina Interna&area=Hospitalización Piso 2&rol=empleado&estado=activo" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar usuario">Editar</a>
                                    <button class="btn-tbl btn-tbl-baja btn-toggle-baja" type="button" title="Dar de baja / reactivar">Baja</button>
                                    <button class="btn-tbl btn-tbl-delete" type="button" title="Eliminar usuario">Borrar</button>
                                </td>
                            </tr>

                            <!-- Usuario 5: Empleado Inactivo -->
                            <tr data-rol="empleado" data-estado="inactivo"
                                data-nombre="Laura"
                                data-paterno="Gómez"
                                data-materno="Ruiz"
                                data-cedula="EMP-40199"
                                data-depto="Cirugía"
                                data-area="Quirófano Central"
                                data-rol-text="Empleado"
                                data-estado-text="Inactivo"
                                data-fecha="22/08/2024">
                                <td class="cell-cedula">EMP-40199</td>
                                <td><strong>Laura Gómez Ruiz</strong></td>
                                <td>Cirugía</td>
                                <td>Quirófano Central</td>
                                <td><span class="status-badge badge-rol-empleado">Empleado</span></td>
                                <td><span class="status-badge badge-inactivo">Inactivo</span></td>
                                <td class="cell-actions">
                                    <button class="btn-tbl btn-tbl-view btn-view-user" type="button" title="Ver más información">Ver</button>
                                    <a href="formulario_usuario.php?cedula=EMP-40199&nombre=Laura&paterno=Gómez&materno=Ruiz&depto=Cirugía&area=Quirófano Central&rol=empleado&estado=inactivo" class="btn-tbl btn-tbl-edit" style="text-decoration:none;" title="Editar usuario">Editar</a>
                                    <button class="btn-tbl btn-tbl-baja btn-toggle-baja" type="button" title="Dar de baja / reactivar">Reactivar</button>
                                    <button class="btn-tbl btn-tbl-delete" type="button" title="Eliminar usuario">Borrar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>

    </div>

    <!-- Modal Detallado: Ver Más Información del Usuario -->
    <div id="modalDetalleUsuario" class="modal-backdrop">
        <div class="modal-card" style="max-width: 580px;">
            <div class="modal-header">
                <h3>Expediente del Usuario</h3>
                <button type="button" class="modal-close" id="btnCloseUserModal">&times;</button>
            </div>
            
            <!-- Tarjeta Cabecera del Usuario -->
            <div class="user-detail-header-card">
                <div class="user-detail-avatar" id="detailAvatar">U</div>
                <div class="user-detail-title">
                    <h4 id="detailNombreCompleto">Nombre del Usuario</h4>
                    <p id="detailCedulaBadge">No. Empleado: EMP-00000</p>
                </div>
            </div>

            <!-- Grilla con toda la información completa -->
            <div class="user-detail-grid">
                <div class="user-detail-item">
                    <div class="user-detail-label">Nombre(s)</div>
                    <div class="user-detail-val" id="detailNombre">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Apellidos</div>
                    <div class="user-detail-val" id="detailApellidos">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Cédula / No. Empleado</div>
                    <div class="user-detail-val" id="detailCedula">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Rol del Sistema</div>
                    <div class="user-detail-val" id="detailRol">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Departamento</div>
                    <div class="user-detail-val" id="detailDepto">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Área Específica</div>
                    <div class="user-detail-val" id="detailArea">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Estado de la Cuenta</div>
                    <div class="user-detail-val" id="detailEstado">--</div>
                </div>
                <div class="user-detail-item">
                    <div class="user-detail-label">Fecha de Registro</div>
                    <div class="user-detail-val" id="detailFecha">--</div>
                </div>
                <div class="user-detail-item user-detail-full">
                    <div class="user-detail-label">Permisos de Acceso</div>
                    <div class="user-detail-val" id="detailPermisos" style="font-weight: 500; font-size: 13px;">--</div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="btnCerrarDetalle">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="dashboard.js"></script>
    <script>
        // Lógica interactiva específica de usuarios
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalDetalleUsuario');
            const btnClose = document.getElementById('btnCloseUserModal');
            const btnCerrar = document.getElementById('btnCerrarDetalle');

            function cerrarModal() {
                if (modal) modal.classList.remove('open');
            }

            if (btnClose) btnClose.addEventListener('click', cerrarModal);
            if (btnCerrar) btnCerrar.addEventListener('click', cerrarModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) cerrarModal();
                });
            }

            // Abrir modal con "Ver más información del usuario"
            document.querySelectorAll('.btn-view-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    if (!tr) return;

                    const nombre = tr.dataset.nombre || '';
                    const paterno = tr.dataset.paterno || '';
                    const materno = tr.dataset.materno || '';
                    const cedula = tr.dataset.cedula || '';
                    const depto = tr.dataset.depto || '';
                    const area = tr.dataset.area || '';
                    const rolText = tr.dataset.rolText || '';
                    const estadoText = tr.dataset.estadoText || '';
                    const fecha = tr.dataset.fecha || 'Reciente';

                    document.getElementById('detailAvatar').textContent = nombre.charAt(0).toUpperCase();
                    document.getElementById('detailNombreCompleto').textContent = `${nombre} ${paterno} ${materno}`;
                    document.getElementById('detailCedulaBadge').textContent = `Cédula / No. Empleado: ${cedula}`;

                    document.getElementById('detailNombre').textContent = nombre;
                    document.getElementById('detailApellidos').textContent = `${paterno} ${materno}`;
                    document.getElementById('detailCedula').textContent = cedula;
                    document.getElementById('detailRol').textContent = rolText;
                    document.getElementById('detailDepto').textContent = depto;
                    document.getElementById('detailArea').textContent = area;
                    document.getElementById('detailEstado').textContent = estadoText;
                    document.getElementById('detailFecha').textContent = fecha;

                    let permisos = '';
                    const rol = tr.dataset.rol;
                    if (rol === 'director') {
                        permisos = '👑 Director General: Control total. Puede dar de alta, editar, dar de baja y eliminar usuarios. Acceso a todo el hospital.';
                    } else if (rol === 'admin') {
                        permisos = '🛡️ Administrador: Acceso a CRUD básico hospitalario y consulta de lista de empleados.';
                    } else {
                        permisos = '🩺 Empleado: Acceso operativo a gestión de pacientes, atenciones y seguimientos clínicos.';
                    }
                    document.getElementById('detailPermisos').textContent = permisos;

                    modal.classList.add('open');
                });
            });

            // Acción Dar de Baja / Reactivar
            document.querySelectorAll('.btn-toggle-baja').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    if (!tr) return;
                    const nombre = tr.dataset.nombre;
                    const estado = tr.dataset.estado;

                    if (estado === 'activo') {
                        if (confirm(`¿Deseas dar de baja al usuario "${nombre}"? No podrá acceder al sistema.`)) {
                            tr.dataset.estado = 'inactivo';
                            tr.dataset.estadoText = 'Inactivo';
                            const badge = tr.querySelector('.badge-activo');
                            if (badge) {
                                badge.className = 'status-badge badge-inactivo';
                                badge.textContent = 'Inactivo';
                            }
                            btn.textContent = 'Reactivar';
                        }
                    } else {
                        if (confirm(`¿Deseas reactivar al usuario "${nombre}"?`)) {
                            tr.dataset.estado = 'activo';
                            tr.dataset.estadoText = 'Activo';
                            const badge = tr.querySelector('.badge-inactivo');
                            if (badge) {
                                badge.className = 'status-badge badge-activo';
                                badge.textContent = 'Activo';
                            }
                            btn.textContent = 'Baja';
                        }
                    }
                });
            });

            // Filtros rápidos por botones (Todos, Empleados, Admin, Director, Activos)
            document.querySelectorAll('.btn-filter-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.btn-filter-user').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filter = btn.dataset.filter;
                    const rows = document.querySelectorAll('#patientsTable tbody tr');

                    rows.forEach(row => {
                        if (filter === 'todos') {
                            row.style.display = '';
                        } else if (filter === 'activo') {
                            row.style.display = (row.dataset.estado === 'activo') ? '' : 'none';
                        } else {
                            row.style.display = (row.dataset.rol === filter) ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
