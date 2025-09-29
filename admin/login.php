<?php
// admin/login.php
declare(strict_types=1);

session_start();

if (isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$adminUser = $_ENV['ADMIN_USER'] ?? null;
$adminPassHash = $_ENV['ADMIN_PASS_HASH'] ?? null;

$error = '';

if ($adminUser === null || $adminPassHash === null) {
    $error = 'Credenciales del panel no configuradas. Contactá al administrador.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if (hash_equals($adminUser, $usuario) && password_verify($clave, $adminPassHash)) {
        $_SESSION['admin'] = $usuario;
        header('Location: index.php');
        exit;
    }

    $error = 'Usuario o contraseña incorrectos.';
}

require_once __DIR__ . '/includes/layouts/panel.php';

adminLayoutHeader([
    'title' => 'Login Admin - Portfolio',
    'showHeader' => false,
    'bodyClass' => 'bg-panel min-h-screen flex items-center justify-center font-body text-white',
    'mainWrapperClass' => 'w-full max-w-md px-6',
]);
?>
        <div class="w-full rounded-3xl border border-white/10 bg-panelCard/80 p-10 shadow-glow">
            <div class="mb-8 text-center">
                <p class="text-xs uppercase tracking-[0.35em] text-panelAccent/70">Acceso</p>
                <h1 class="mt-3 text-2xl font-semibold text-white">Panel de Administración</h1>
                <p class="mt-2 text-sm text-white/60">Ingresá tus credenciales para continuar.</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="usuario" class="mb-2 block text-sm font-medium text-white/70">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required 
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>
                
                <div>
                    <label for="clave" class="mb-2 block text-sm font-medium text-white/70">Contraseña</label>
                    <input type="password" id="clave" name="clave" required 
                        class="w-full rounded-2xl border border-white/10 bg-panelMuted/60 px-4 py-3 text-sm text-white focus:border-panelAccent focus:outline-none focus:ring-2 focus:ring-panelAccent/30">
                </div>
                
                <button type="submit" 
                    class="w-full rounded-full bg-panelAccent px-6 py-3 text-sm font-semibold text-panel transition hover:bg-panelAccent/90">
                    Ingresar al Panel
                </button>
            </form>
            
        </div>
<?php adminLayoutFooter(); ?>
