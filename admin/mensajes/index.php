<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';
require_once __DIR__ . '/../includes/layouts/panel.php';

// Obtener todos los mensajes
$sql = "SELECT * FROM mensajes ORDER BY fecha DESC";
$stmt = $conexion->query($sql);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!function_exists('resumirMensaje')) {
    function resumirMensaje(string $texto, int $limite = 80): string
    {
        if (function_exists('mb_strimwidth')) {
            return mb_strimwidth($texto, 0, $limite, '…', 'UTF-8');
        }

        if (strlen($texto) > $limite) {
            return substr($texto, 0, $limite - 1) . '…';
        }

        return $texto;
    }
}

adminLayoutHeader([
    'title' => 'Mensajes Recibidos',
    'subtitle' => 'Consulta los mensajes enviados a través del portfolio.',
    'actions' => [
        [
            'href' => '../index.php',
            'label' => ' Volver al Panel',
            'variant' => 'secondary',
        ],
    ],
]);
?>
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-panelCard/80 shadow-glow">
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-panelMuted/60">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">ID</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Nombre</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Motivo</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Mensaje</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php if (empty($mensajes)): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-white/60">
                                        No hay mensajes registrados todavía.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mensajes as $mensaje): ?>
                                    <tr class="transition hover:bg-white/5">
                                        <td class="px-4 py-3 text-sm text-white/60">#<?= $mensaje['id_mensaje'] ?></td>
                                        <td class="px-4 py-3 text-sm font-semibold text-white"><?= htmlspecialchars($mensaje['nombre']) ?></td>
                                        <td class="px-4 py-3 text-sm text-white/70">
                                            <a href="mailto:<?= htmlspecialchars($mensaje['email']) ?>" class="text-panelAccent hover:underline">
                                                <?= htmlspecialchars($mensaje['email']) ?>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-white/70"><?= htmlspecialchars($mensaje['motivo']) ?></td>
                                        <td class="px-4 py-3 text-sm text-white/70">
                                            <?= htmlspecialchars(resumirMensaje($mensaje['mensaje'])) ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-white/50">
                                            <?= htmlspecialchars($mensaje['fecha']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php adminLayoutFooter(); ?>
