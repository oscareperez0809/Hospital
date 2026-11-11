<?php
session_start();
require_once "conexion.php";
$paginaActiva = 'usuarios';
$tituloHeader = 'Gestión y Control de Usuarios';

// Control de acceso por rol: Empleado no puede entrar aquí
$rolUsuario = $_SESSION['rol'] ?? 'empleado';
if ($rolUsuario === 'empleado') {
    header("Location: dashboard.php");
    exit();
}

$mensajeExito = '';
$mensajeError = '';

// Mensajes de alerta según la URL (Registrado, Actualizado, Eliminado o Error)
if (isset($_GET['registrado'])) {
    $mensajeExito = 'Usuario registrado exitosamente.';
} elseif (isset($_GET['actualizado'])) {
    $mensajeExito = 'Usuario actualizado exitosamente.';
} elseif (isset($_GET['eliminado'])) {
    $mensajeExito = 'Usuario eliminado correctamente.';
} elseif (isset($_GET['error'])) {
    $mensajeError = 'No fue posible completar la operación en usuarios.';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Sistema Hospitalario ISSSTE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
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
        <?php include 'sidebar.php'; ?>
        <main class="main-wrapper">
            <?php include 'header.php'; ?>
            <section class="content-body">

                <?php if (!empty($mensajeExito)): ?>
                    <div style="background-color: rgba(34, 197, 94, 0.15); border: 1.5px solid #22c55e; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <span>✓</span> <?= htmlspecialchars($mensajeExito) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                    <div style="background-color: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #991b1b; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <span>⚠</span> <?= htmlspecialchars($mensajeError) ?>
                    </div>
                <?php endif; ?>

                <!-- Aviso Informativo para Administrador (Solo Lectura) -->
                <?php if ($rolUsuario === 'admin'): ?>
                    <div style="background-color: rgba(59, 130, 246, 0.12); border: 1.5px solid #3b82f6; color: #1e40af; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
                        <span>ℹ</span> <strong>Modo Administrador (Solo Lectura):</strong> Puedes consultar el directorio y datos de los usuarios del sistema, sin privilegios para registrar, modificar o eliminar registros.
                    </div>
                <?php endif; ?>

                <!-- Barra de Herramientas -->
                <div class="patients-toolbar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <!-- Botón Registrar visible UNICAMENTE para el Director General -->
                        <?php if ($rolUsuario === 'director'): ?>
                            <a href="formulario_usuario.php" class="btn-registrar" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                REGISTRAR
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn-chip btn-filter-user active" data-filter="todos" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; font-weight: 600; font-size: 13px;">Todos</button>
                        <button type="button" class="btn-chip btn-filter-user" data-filter="empleado" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; font-weight: 600; font-size: 13px;">Empleados</button>
                        <button type="button" class="btn-chip btn-filter-user" data-filter="admin" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; font-weight: 600; font-size: 13px;">Administradores</button>
                        <button type="button" class="btn-chip btn-filter-user" data-filter="director" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card); cursor: pointer; font-weight: 600; font-size: 13px;">Director General</button>
                    </div>

                    <div class="search-pill-container">
                        <input type="text" id="searchPatients" class="search-pill-input" placeholder="BUSCAR" autocomplete="off">
                        <span class="search-pill-icon" title="Buscar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Usuarios -->
                <div class="patients-table-wrapper">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>Cédula / No. Emp</th>
                                <th>Nombre Completo</th>
                                <th>Departamento</th>
                                <th>Área</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hayUsuariosBD = false;
                            if (!empty($conectar)) {
                                $consultaUsuarios = mysqli_query($conectar, "SELECT * FROM usuarios ORDER BY nombre ASC");
                                if ($consultaUsuarios && mysqli_num_rows($consultaUsuarios) > 0) {
                                    $hayUsuariosBD = true;
                                    while ($u = mysqli_fetch_assoc($consultaUsuarios)) {
                                        $ced = htmlspecialchars($u['cedula']);
                                        $nom = htmlspecialchars($u['nombre']);
                                        $pat = htmlspecialchars($u['ape_pat'] ?? '');
                                        $mat = htmlspecialchars($u['ape_mat'] ?? '');
                                        $dep = htmlspecialchars($u['departamento'] ?? '--');
                                        $are = htmlspecialchars($u['area'] ?? '--');
                                        $rolVal = $u['rol'] ?? 'empleado';

                                        // Formatear etiqueta de rol legible
                                        $rolLabel = 'Empleado';
                                        if ($rolVal === 'admin') $rolLabel = 'Administrador';
                                        if ($rolVal === 'director') $rolLabel = 'Director General';
                            ?>
                                        <tr data-rol="<?= htmlspecialchars($rolVal) ?>">
                                            <td class="cell-cedula"><strong><?= $ced ?></strong></td>
                                            <td><?= "{$nom} {$pat} {$mat}" ?></td>
                                            <td><?= $dep ?></td>
                                            <td><?= $are ?></td>
                                            <td>
                                                <span style="padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                                                    <?= $rolLabel ?>
                                                </span>
                                            </td>
                                            <td class="cell-actions">
                                                <!-- Botón Ver (disponible para ambos roles con permiso) -->
                                                <a href="ver_usuario.php?cedula=<?= urlencode($u['cedula']) ?>" class="btn-tbl btn-tbl-view" style="text-decoration:none;">Ver</a>

                                                <!-- Opciones de Edición y Eliminación exclusivas para el DIRECTOR GENERAL -->
                                                <?php if ($rolUsuario === 'director'): ?>
                                                    <a href="modificar_usuario.php?cedula=<?= urlencode($u['cedula']) ?>" class="btn-tbl btn-tbl-edit" style="text-decoration:none;">Editar</a>
                                                    <a href="eliminar_usuario.php?cedula=<?= urlencode($u['cedula']) ?>" class="btn-tbl btn-tbl-delete" style="text-decoration:none;" onclick="return confirm('¿Seguro que deseas eliminar este usuario del sistema?');">Eliminar</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                            }

                            if (!$hayUsuariosBD):
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding: 48px 20px; color: var(--text-secondary);">
                                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--text-primary);">
                                            No hay usuarios registrados en la base de datos
                                        </div>
                                        <p style="font-size: 13.5px;">
                                            <?= ($rolUsuario === 'director') ? 'Haga clic en el botón <strong>REGISTRAR</strong> para agregar al primer usuario.' : 'No se encontraron registros disponibles.' ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>
    </div>

    <!-- Script de filtrado y búsqueda instantánea -->
    <script src="dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Filtrado por botones de rol
            document.querySelectorAll('.btn-filter-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.btn-filter-user').forEach(b => {
                        b.classList.remove('active');
                        b.style.background = 'var(--bg-card)';
                    });
                    btn.classList.add('active');
                    btn.style.background = 'var(--primary-color, #0284c7)';
                    btn.style.color = '#fff';

                    const filter = btn.dataset.filter;
                    const rows = document.querySelectorAll('#patientsTable tbody tr');

                    rows.forEach(row => {
                        if (!row.dataset.rol) return; // Fila vacía o mensaje
                        if (filter === 'todos') {
                            row.style.display = '';
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