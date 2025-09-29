<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';
require_once __DIR__ . '/../includes/layouts/panel.php';

// Obtener todos los proyectos con sus tecnologías
$sql = "SELECT p.*, 
               GROUP_CONCAT(t.name_tecnologia SEPARATOR ', ') as tecnologias
        FROM proyectos p 
        LEFT JOIN proyectos_tecnologias pt ON p.id_project = pt.id_project 
        LEFT JOIN tecnologias t ON pt.id_tecnologia = t.id_tecnologia 
        GROUP BY p.id_project 
        ORDER BY p.id_project DESC";
$stmt = $conexion->query($sql);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

adminLayoutHeader([
    'title' => 'Gestionar Proyectos',
    'subtitle' => 'Administra tus proyectos, estados y tecnologías asociadas.',
    'actions' => [
        [
            'href' => 'crear.php',
            'label' => ' Crear Nuevo Proyecto',
            'variant' => 'primary',
        ],
        [
            'href' => '../index.php',
            'label' => ' Volver al Panel',
            'variant' => 'secondary',
        ],
    ],
]);
?>
        <div class="space-y-6">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="overflow-hidden rounded-3xl border border-white/10 bg-panelCard/80 shadow-glow">
                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-panelMuted/60">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">ID</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Nombre</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Descripción</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Tecnologías</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Estado</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php if (empty($proyectos)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-white/60">
                                            No hay proyectos registrados todavía. Crea uno nuevo para comenzar.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($proyectos as $proyecto): ?>
                                        <tr class="transition hover:bg-white/5">
                                            <td class="px-4 py-3 text-sm text-white/60">#<?= $proyecto['id_project'] ?></td>
                                            <td class="px-4 py-3 text-sm font-semibold text-white"><?= htmlspecialchars($proyecto['name_project']) ?></td>
                                            <td class="px-4 py-3 text-sm text-white/70"><?= htmlspecialchars($proyecto['description']) ?></td>
                                            <td class="px-4 py-3 text-sm text-white/60">
                                                <?php if (!empty($proyecto['tecnologias'])): ?>
                                                    <div class="flex flex-wrap gap-2">
                                                        <?php foreach (explode(', ', $proyecto['tecnologias']) as $tech): ?>
                                                            <span class="rounded-full border border-white/10 bg-panelMuted/50 px-3 py-1 text-xs font-semibold text-white/70"><?= htmlspecialchars(trim($tech)) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="italic text-white/40">Sin tecnologías asignadas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <?php if ($proyecto['state']): ?>
                                                    <span class="inline-flex items-center gap-2 rounded-full border border-panelAccent/50 bg-panelAccent/10 px-3 py-1 text-xs font-semibold text-panelAccent">
                                                        <span>Activo</span>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-300/40 bg-amber-300/10 px-3 py-1 text-xs font-semibold text-amber-200">
                                                        <span>Inactivo</span>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <a href="editar.php?id=<?= $proyecto['id_project'] ?>" class="inline-flex items-center gap-2 rounded-full border border-panelAccent/50 bg-panelAccent/10 px-3 py-2 text-xs font-semibold text-panelAccent transition hover:bg-panelAccent/20">
                                                        Editar
                                                    </a>
                                                    <a href="eliminar.php?id=<?= $proyecto['id_project'] ?>" class="inline-flex items-center gap-2 rounded-full border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-200 transition hover:bg-red-500/20" onclick="return confirm('¿Eliminar este proyecto?')">
                                                        Eliminar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php adminLayoutFooter(); ?>
