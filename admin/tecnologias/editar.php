<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener la tecnología a editar
$id = $_GET['id'] ?? 0;
$tecnologia = null;

if ($id) {
    $sql = "SELECT * FROM tecnologias WHERE id_tecnologia = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $tecnologia = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$tecnologia) {
    $_SESSION['error'] = "Tecnología no encontrada";
    header("Location: index.php");
    exit;
}

// Obtener categorías para el select
$sql_categorias = "SELECT * FROM categorias_tecnologias ORDER BY nombre_categoria";
$categorias = $conexion->query($sql_categorias)->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/layouts/panel.php';

adminLayoutHeader([
    'title' => 'Editar Tecnología',
    'subtitle' => 'Actualiza los datos de la tecnología seleccionada.',
    'actions' => [
        [
            'href' => 'index.php',
            'label' => ' Volver a Tecnologías',
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
                <input type="hidden" name="id_tecnologia" value="<?= $tecnologia['id_tecnologia'] ?>">

                <div class="space-y-2">
                    <label for="name_tecnologia" class="block text-sm font-medium text-white/70">Nombre de la tecnología</label>
                    <input type="text" id="name_tecnologia" name="name_tecnologia" required maxlength="60"
                        value="<?= htmlspecialchars($tecnologia['name_tecnologia']) ?>"
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>

                <div class="space-y-2">
                    <label for="version" class="block text-sm font-medium text-white/70">Versión <span class="text-white/40">(opcional)</span></label>
                    <input type="text" id="version" name="version" maxlength="10" placeholder="Ej: 18.2.0"
                        value="<?= htmlspecialchars($tecnologia['version']) ?>"
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white placeholder:text-white/30 focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>

                <div class="space-y-2">
                    <label for="id_categoria" class="block text-sm font-medium text-white/70">Categoría</label>
                    <select id="id_categoria" name="id_categoria" required
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= $categoria['id_categoria'] == $tecnologia['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center rounded-full bg-panelAccent px-5 py-3 text-sm font-semibold text-panel transition hover:bg-panelAccent/85">
                        Actualizar Tecnología
                    </button>
                </div>
            </form>
        </div>
<?php adminLayoutFooter(); ?>
