<?php 
// admin/index.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once '../php/conexion.php';
require_once __DIR__ . '/includes/layouts/panel.php';

// Obtener estadísticas
$sql_proyectos = "SELECT COUNT(*) as total FROM proyectos";
$sql_mensajes = "SELECT COUNT(*) as total FROM mensajes";
$sql_tecnologias = "SELECT COUNT(*) as total FROM tecnologias";
$sql_categorias = "SELECT COUNT(*) as total FROM categorias_tecnologias";

$total_proyectos = $conexion->query($sql_proyectos)->fetch(PDO::FETCH_ASSOC)['total'];
$total_mensajes = $conexion->query($sql_mensajes)->fetch(PDO::FETCH_ASSOC)['total'];
$total_tecnologias = $conexion->query($sql_tecnologias)->fetch(PDO::FETCH_ASSOC)['total'];
$total_categorias = $conexion->query($sql_categorias)->fetch(PDO::FETCH_ASSOC)['total'];

$adminName = htmlspecialchars($_SESSION['admin']);

adminLayoutHeader([
    'title' => 'Panel de Administración',
    'subtitle' => "Bienvenido, {$adminName}",
    'actions' => [
        [
            'href' => 'logout.php',
            'label' => 'Cerrar sesión',
            'variant' => 'danger',
        ],
    ],
]);
?>
        <!-- Estadísticas -->
        <div class="mb-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-3xl border border-white/10 bg-panelCard/80 p-6 shadow-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">Proyectos</p>
                        <p class="mt-3 text-3xl font-semibold text-white"><?= $total_proyectos ?></p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-white/50">Total de proyectos activos y archivados.</p>
            </article>

            <article class="rounded-3xl border border-white/10 bg-panelCard/80 p-6 shadow-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">Categorías</p>
                        <p class="mt-3 text-3xl font-semibold text-white"><?= $total_categorias ?></p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-white/50">Organizá tus tecnologías por áreas clave.</p>
            </article>

            <article class="rounded-3xl border border-white/10 bg-panelCard/80 p-6 shadow-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">Tecnologías</p>
                        <p class="mt-3 text-3xl font-semibold text-white"><?= $total_tecnologias ?></p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-white/50">Stack disponible para asignar en cada proyecto.</p>
            </article>

            <article class="rounded-3xl border border-white/10 bg-panelCard/80 p-6 shadow-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">Mensajes</p>
                        <p class="mt-3 text-3xl font-semibold text-white"><?= $total_mensajes ?></p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-white/50">Consultas recibidas vía portfolio.</p>
            </article>
        </div>

        <!-- Menú de Navegación -->
        <div class="rounded-3xl border border-white/10 bg-panelCard/80">
            <div class="grid grid-cols-1 divide-y divide-white/10 md:grid-cols-2 md:divide-y-0 md:divide-x lg:grid-cols-4">
                <a href="proyectos/" class="group flex flex-col gap-3 p-6 transition hover:bg-white/5">
                    <h3 class="font-heading text-lg font-semibold text-white group-hover:text-panelAccent">Gestionar Proyectos</h3>
                    <p class="text-sm text-white/60">Crear, editar y asignar tecnologías.</p>
                </a>

                <a href="categorias/" class="group flex flex-col gap-3 p-6 transition hover:bg-white/5">
                    <h3 class="font-heading text-lg font-semibold text-white group-hover:text-emerald-300">Gestionar Categorías</h3>
                    <p class="text-sm text-white/60">Organiza áreas de conocimiento.</p>
                </a>

                <a href="tecnologias/" class="group flex flex-col gap-3 p-6 transition hover:bg-white/5">
                    <h3 class="font-heading text-lg font-semibold text-white group-hover:text-indigo-300">Gestionar Tecnologías</h3>
                    <p class="text-sm text-white/60">Actualizá tu stack y versiones.</p>
                </a>

                <a href="mensajes/" class="group flex flex-col gap-3 p-6 transition hover:bg-white/5">
                    <h3 class="font-heading text-lg font-semibold text-white group-hover:text-amber-200">Ver Mensajes</h3>
                    <p class="text-sm text-white/60">Respondé consultas sin perder contexto.</p>
                </a>
            </div>
        </div>
<?php adminLayoutFooter(); ?>
