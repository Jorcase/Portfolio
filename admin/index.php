<?php 
// admin/index.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once '../php/conexion.php';

// Obtener estadísticas
$sql_proyectos = "SELECT COUNT(*) as total FROM proyectos";
$sql_mensajes = "SELECT COUNT(*) as total FROM mensajes";
$sql_tecnologias = "SELECT COUNT(*) as total FROM tecnologias";
$sql_categorias = "SELECT COUNT(*) as total FROM categorias_tecnologias";

$total_proyectos = $conexion->query($sql_proyectos)->fetch(PDO::FETCH_ASSOC)['total'];
$total_mensajes = $conexion->query($sql_mensajes)->fetch(PDO::FETCH_ASSOC)['total'];
$total_tecnologias = $conexion->query($sql_tecnologias)->fetch(PDO::FETCH_ASSOC)['total'];
$total_categorias = $conexion->query($sql_categorias)->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"> Panel de Administración</h1>
                    <p class="text-gray-600">Bienvenido, <?= htmlspecialchars($_SESSION['admin']) ?></p>
                </div>
                <nav class="flex space-x-4">
                    <a href="logout.php" class="text-red-600 hover:text-red-800 font-semibold"> Cerrar sesión</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <span class="text-blue-600 text-xl">📋</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800"><?= $total_proyectos ?></h2>
                        <p class="text-gray-600">Proyectos</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <span class="text-green-600 text-xl">📁</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800"><?= $total_categorias ?></h2>
                        <p class="text-gray-600">Categorías</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <span class="text-purple-600 text-xl">⚙️</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800"><?= $total_tecnologias ?></h2>
                        <p class="text-gray-600">Tecnologías</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <span class="text-yellow-600 text-xl">💌</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800"><?= $total_mensajes ?></h2>
                        <p class="text-gray-600">Mensajes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú de Navegación -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-x divide-y md:divide-y-0">
                <a href="proyectos/" class="p-6 hover:bg-gray-50 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition">
                            <span class="text-blue-600 text-xl">📋</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Gestionar Proyectos</h3>
                        <p class="text-sm text-gray-600">Crear y editar proyectos</p>
                    </div>
                </a>

                <a href="categorias/" class="p-6 hover:bg-gray-50 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition">
                            <span class="text-green-600 text-xl">📁</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Gestionar Categorías</h3>
                        <p class="text-sm text-gray-600">Administrar categorías</p>
                    </div>
                </a>

                <a href="tecnologias/" class="p-6 hover:bg-gray-50 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200 transition">
                            <span class="text-purple-600 text-xl">⚙️</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Gestionar Tecnologías</h3>
                        <p class="text-sm text-gray-600">Administrar tecnologías</p>
                    </div>
                </a>

                <a href="mensajes/" class="p-6 hover:bg-gray-50 transition group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200 transition">
                            <span class="text-yellow-600 text-xl">💌</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Ver Mensajes</h3>
                        <p class="text-sm text-gray-600">Mensajes recibidos</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>