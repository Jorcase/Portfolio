<?php
// index.php
require_once 'php/conexion.php';
require_once 'includes/funciones.php';

// Sesión para token anti doble envío
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['form_token'] = $_SESSION['form_token'] ?? bin2hex(random_bytes(32));
$formToken = $_SESSION['form_token'];

// Obtener proyectos y tecnologías
$proyectos = obtenerProyectos($conexion);
$tecnologias = obtenerTecnologias($conexion);
$habilidades = obtenerHabilidades($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Jorge Castillo</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" type="text/css" href="clase.css">
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-md fixed w-full top-0 z-10">
        <div class="container mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <h1 class="text-2xl font-bold text-blue-800">Castillo Chaustre Jorge Alberto</h1>
                <p class="text-gray-600"><strong>Tecnicatura en Programación</strong> - Programación en Aplicaciones Web</p>
            </div>
            
            <nav class="w-full md:w-auto">
                <ul class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 justify-center">
                    <li><a href="#inicio" class="block py-2 px-4 hover:bg-blue-100 rounded transition">Inicio</a></li>
                    <li><a href="#sobremi" class="block py-2 px-4 hover:bg-blue-100 rounded transition">Sobre mí</a></li>
                    <li><a href="#proyectos" class="block py-2 px-4 hover:bg-blue-100 rounded transition">Proyectos</a></li>
                    <li><a href="#contacto" class="block py-2 px-4 hover:bg-blue-100 rounded transition">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="pt-24">
        <!-- Sección Inicio -->
        <section id="inicio" class="min-h-screen flex items-center py-12 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6 text-blue-900">Hola soy Jorge</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto">Estudiante de programación en la <strong class="text-blue-700">Universidad Nacional de Salta</strong>, especializándome en <strong class="text-blue-700">ciberseguridad y redes</strong>.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#proyectos" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">Mis proyectos</a>
                    <a href="#contacto" class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 font-bold py-3 px-6 rounded-lg transition">Contactame</a>
                </div>
            </div>
        </section>

        <!-- Sección Sobre Mí -->
        <section id="sobremi" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Sobre Mí</h2>
                <div class="max-w-4xl mx-auto">
                    <p class="text-lg mb-8 text-center">Mi aprendizaje en programación comenzó en 2021 con el inicio de mi carrera universitaria</p>
                    
                    <h3 class="text-2xl font-semibold mb-6 text-blue-800">Habilidades técnicas:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php
                        $count = 0;
                        $half = ceil(count($habilidades) / 2);
                        foreach ($habilidades as $categoria => $techs):
                            if ($count % $half == 0) {
                                echo '<div>';
                            }
                        ?>
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-blue-700 mb-2"><?= htmlspecialchars($categoria) ?></h4>
                                <ul class="space-y-1">
                                    <?php foreach ($techs as $tech): ?>
                                        <li class="flex items-center">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                            <?= htmlspecialchars($tech) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                            $count++;
                            if ($count % $half == 0 || $count == count($habilidades)) {
                                echo '</div>';
                            }
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección Proyectos -->
        <section id="proyectos" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12 text-blue-900">Mis Proyectos</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php foreach ($proyectos as $proyecto): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?= $proyecto['state'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?> mb-3">
                                <?= $proyecto['state'] ? 'Completado' : 'En desarrollo' ?>
                            </span>
                            <h3 class="text-xl font-bold mb-3 text-blue-800"><?= htmlspecialchars($proyecto['name_project']) ?></h3>
                            <p class="mb-4 text-gray-600"><?= htmlspecialchars($proyecto['description']) ?></p>
                            
                            <?php if (!empty($proyecto['tecnologias'])): ?>
                                <div class="mb-4">
                                    <strong class="text-blue-700 block mb-2">Tecnologías utilizadas:</strong>
                                    <div class="flex flex-wrap gap-2">
                                        <?php 
                                        $techs = explode(', ', $proyecto['tecnologias']);
                                        foreach ($techs as $tech): 
                                        ?>
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?= htmlspecialchars(trim($tech)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

<!-- Sección Contacto -->
  <section id="contacto" class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-2xl">
      <h2 class="text-3xl font-bold text-center mb-8 text-blue-900">Contacto</h2>

      <form id="form-contacto" class="space-y-6">
        <input type="hidden" name="token" value="<?= htmlspecialchars($formToken) ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 font-medium">Nombre:</label>
            <input type="text" name="nombre" required
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block mb-2 font-medium">Apellido:</label>
            <input type="text" name="apellido" required
              class="w-full px-4 py-2 border rounded-lg">
          </div>
        </div>

        <div>
          <label class="block mb-2 font-medium">Email:</label>
          <input type="email" name="email" required
            class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
          <label class="block mb-2 font-medium">Consulta</label>
          <select name="motivo" required class="w-full px-4 py-2 border rounded-lg">
            <option value="">Seleccione motivo</option>
            <option value="trabajo">Trabajo</option>
            <option value="facultad">Facultad</option>
            <option value="emergencia">Emergencia</option>
          </select>
        </div>

        <div>
          <label class="block mb-2 font-medium">Mensaje</label>
          <textarea name="mensaje" required minlength="10" rows="5"
            class="w-full px-4 py-2 border rounded-lg"></textarea>
        </div>
        <div id="status-box" class="mt-4 text-center"></div>
        <div class="text-center">
          <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
            Enviar mensaje
          </button>
        </div>
      </form>
      
    </div>
  </section>
</main>

<footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; 2024 Castillo Chaustre Jorge Alberto - Todos los derechos reservados</p>
        <p class="mt-2">📍 Salta, Argentina</p>
        <p class="mt-2">📧 jorcascero@gmail.com</p>
    </div>
</footer>

<!-- Script para cargar proyectos dinámicos (esto lo dejamos como estaba) -->
<script>
    fetch('php/crud/listar_proyectos.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('proyectos-lista');
            container.innerHTML = "";
            data.forEach(p => {
                const card = document.createElement('div');
                card.className = "bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition";
                card.innerHTML = `
                    <h3 class="text-xl font-bold mb-3 text-blue-800">${p.name_project}</h3>
                    <p class="mb-4">${p.description}</p>
                    <p class="mb-2"><strong class="text-blue-700">Estado:</strong> ${p.state == 1 ? "Activo" : "Inactivo"}</p>
                `;
                container.appendChild(card);
            });
        })
        .catch(err => console.error("Error cargando proyectos:", err));
</script>

 <script src="js/contacto.js"></script>
</body>
</html>
