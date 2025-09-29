<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';
require_once __DIR__ . '/../includes/layouts/panel.php';

// Obtener todas las tecnologías con sus categorías
$sql = "SELECT t.*, c.nombre_categoria 
        FROM tecnologias t 
        JOIN categorias_tecnologias c ON t.id_categoria = c.id_categoria 
        ORDER BY c.nombre_categoria, t.name_tecnologia";
$stmt = $conexion->query($sql);
$tecnologias = $stmt->fetchAll(PDO::FETCH_ASSOC);

adminLayoutHeader([
    'title' => 'Gestionar Tecnologías',
    'subtitle' => 'Controla las tecnologías disponibles y su clasificación.',
    'actions' => [
        [
            'href' => 'crear.php',
            'label' => ' Crear Nueva Tecnología',
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
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Versión</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Categoría</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php if (empty($tecnologias)): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-white/60">
                                            No hay tecnologías registradas. Crea una nueva para empezar.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($tecnologias as $tec): ?>
                                        <tr class="transition hover:bg-white/5">
                                            <td class="px-4 py-3 text-sm text-white/60">#<?= $tec['id_tecnologia'] ?></td>
                                            <td class="px-4 py-3 text-sm font-semibold text-white"><?= htmlspecialchars($tec['name_tecnologia']) ?></td>
                                            <td class="px-4 py-3 text-sm text-white/60">
                                                <?php if ($tec['version'] !== null && $tec['version'] !== ''): ?>
                                                    <?= htmlspecialchars($tec['version']) ?>
                                                <?php else: ?>
                                                    <span class="text-white/40">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-white/70"><?= htmlspecialchars($tec['nombre_categoria']) ?></td>
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <a href="editar.php?id=<?= $tec['id_tecnologia'] ?>" class="inline-flex items-center gap-2 rounded-full border border-panelAccent/50 bg-panelAccent/10 px-3 py-2 text-xs font-semibold text-panelAccent transition hover:bg-panelAccent/20">
                                                        Editar
                                                    </a>
                                                    <a href="eliminar.php?id=<?= $tec['id_tecnologia'] ?>" class="inline-flex items-center gap-2 rounded-full border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-200 transition hover:bg-red-500/20" onclick="return confirm('¿Eliminar esta tecnología?')">
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
