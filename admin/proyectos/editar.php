<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';
require_once __DIR__ . '/../includes/layouts/panel.php';

// Obtener el proyecto a editar
$id = $_GET['id'] ?? 0;
$proyecto = null;

if ($id) {
    $sql = "SELECT * FROM proyectos WHERE id_project = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$proyecto) {
    $_SESSION['error'] = "Proyecto no encontrado";
    header("Location: index.php");
    exit;
}

// Obtener todas las tecnologías agrupadas por categoría
$sql_tecnologias = "SELECT t.*, c.nombre_categoria 
                   FROM tecnologias t 
                   JOIN categorias_tecnologias c ON t.id_categoria = c.id_categoria 
                   ORDER BY c.nombre_categoria, t.name_tecnologia";
$tecnologias = $conexion->query($sql_tecnologias)->fetchAll(PDO::FETCH_ASSOC);

// Obtener tecnologías asignadas a este proyecto
$sql_asignadas = "SELECT id_tecnologia FROM proyectos_tecnologias WHERE id_project = ?";
$stmt = $conexion->prepare($sql_asignadas);
$stmt->execute([$id]);
$tecnologias_asignadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

adminLayoutHeader([
    'title' => 'Editar Proyecto',
    'subtitle' => 'Actualiza la información del proyecto seleccionado.',
    'actions' => [
        [
            'href' => 'index.php',
            'label' => ' Volver a Proyectos',
            'variant' => 'secondary',
        ],
    ],
]);
?>
        <div class="space-y-6 rounded-3xl border border-white/10 bg-panelCard/80 p-8 shadow-glow">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="guardar.php" method="POST" class="space-y-6">
                <input type="hidden" name="id_project" value="<?= $proyecto['id_project'] ?>">

                <div class="space-y-2">
                    <label for="name_project" class="block text-sm font-medium text-white/70">Nombre del Proyecto</label>
                    <input type="text" id="name_project" name="name_project" required maxlength="60"
                        value="<?= htmlspecialchars($proyecto['name_project']) ?>"
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-white/70">Descripción</label>
                    <textarea id="description" name="description" required maxlength="400" rows="4"
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30"><?= htmlspecialchars($proyecto['description']) ?></textarea>
                </div>

                <div class="space-y-2">
                    <label for="state" class="block text-sm font-medium text-white/70">Estado</label>
                    <select id="state" name="state" required
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                        <option value="1" <?= $proyecto['state'] ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= !$proyecto['state'] ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <span class="block text-sm font-medium text-white/70">Tecnologías utilizadas</span>
                    <div class="max-h-72 space-y-4 overflow-y-auto rounded-2xl border border-white/10 bg-panelMuted/40 p-4">
                        <?php
                        $categoria_actual = '';
                        foreach ($tecnologias as $tec):
                            if ($categoria_actual != $tec['nombre_categoria']):
                                $categoria_actual = $tec['nombre_categoria'];
                                echo '<h4 class="pt-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/60">' . htmlspecialchars($categoria_actual) . '</h4>';
                            endif;
                        ?>
                            <label class="flex items-center gap-3 rounded-2xl border border-white/5 bg-panelMuted/40 px-4 py-3 text-sm text-white/80">
                                <input type="checkbox" name="tecnologias[]" value="<?= $tec['id_tecnologia'] ?>"
                                    <?= in_array($tec['id_tecnologia'], $tecnologias_asignadas) ? 'checked' : '' ?>
                                    class="h-4 w-4 rounded border-white/20 bg-panel focus:ring-panelAccent/40">
                                <span>
                                    <?= htmlspecialchars($tec['name_tecnologia']) ?>
                                    <?php if (!empty($tec['version'])): ?>
                                        <span class="text-white/40">(<?= htmlspecialchars($tec['version']) ?>)</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center rounded-full bg-panelAccent px-5 py-3 text-sm font-semibold text-panel transition hover:bg-panelAccent/85">
                        Actualizar Proyecto
                    </button>
                </div>
            </form>
        </div>
<?php adminLayoutFooter(); ?>
