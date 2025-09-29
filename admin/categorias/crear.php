<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/layouts/panel.php';

adminLayoutHeader([
    'title' => 'Crear Nueva Categoría',
    'subtitle' => 'Define una categoría para clasificar tus tecnologías.',
    'actions' => [
        [
            'href' => 'index.php',
            'label' => ' Volver a Categorías',
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
                <div class="space-y-2">
                    <label for="nombre_categoria" class="block text-sm font-medium text-white/70">Nombre de la categoría</label>
                    <input type="text" id="nombre_categoria" name="nombre_categoria" required maxlength="100"
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center rounded-full bg-panelAccent px-5 py-3 text-sm font-semibold text-panel transition hover:bg-panelAccent/85">
                        Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
<?php adminLayoutFooter(); ?>
