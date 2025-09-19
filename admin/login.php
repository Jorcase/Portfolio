<?php
// admin/login.php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';
    
    if ($usuario === "admin" && $clave === "1234") {
        $_SESSION['admin'] = $usuario;
        header("Location: index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800"> Panel de Administración</h1>
            <p class="text-gray-600">Ingresa tus credenciales para continuar</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            
            <div>
                <label for="clave" class="block text-sm font-medium text-gray-700 mb-2">Contraseña:</label>
                <input type="password" id="clave" name="clave" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            
            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                Ingresar al Panel
            </button>
        </form>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 text-center">
                 Credenciales por defecto: <strong>admin</strong> / <strong>1234</strong>
            </p>
        </div>
    </div>
</body>
</html>