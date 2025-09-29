<?php
// index.php
require_once 'php/conexion.php';
require_once 'includes/funciones.php';
require_once 'includes/layouts/site.php';

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

// Imagen de fondo para la sección principal (podés dejarla vacía si preferís sólo el degradado).
$heroBackgroundImage = 'images/portfolio2.jpg';
$heroBannerStyle = '';

if ($heroBackgroundImage !== '') {
    $heroBackgroundImageSafe = htmlspecialchars($heroBackgroundImage, ENT_QUOTES, 'UTF-8');
    $heroBannerStyle = "background-image: url('{$heroBackgroundImageSafe}');";
}
// Cabecera con layout
siteLayoutHeader([
    'title'     => 'Portfolio - Jorge Castillo',
    'bodyClass' => 'text-gray-800 font-sans antialiased page-surface'
]);
?>
    <!-- Header -->
    <header class="fixed inset-x-0 top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="container relative mx-auto flex items-center justify-between gap-6 px-4 py-4 md:px-6">
            <div class="flex flex-col text-left">
                <h1 class="text-xl font-bold text-blue-800 sm:text-2xl">Castillo Chaustre Jorge Alberto</h1>
                <p class="text-sm text-gray-600 sm:text-base"><strong>Tecnicatura en Programación</strong> - Programación en Aplicaciones Web</p>
            </div>

            <button id="nav-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 text-slate-600 transition hover:border-blue-500 hover:text-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 md:hidden" aria-expanded="false" aria-controls="primary-nav">
                <span class="sr-only">Abrir menú</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <nav id="primary-nav" class="hidden absolute left-0 right-0 top-full flex-col gap-3 border-t border-slate-200 bg-white px-4 py-4 text-sm font-medium text-slate-700 shadow-lg md:static md:flex md:w-auto md:flex-row md:items-center md:gap-8 md:border-0 md:bg-transparent md:px-0 md:py-0 md:text-base md:shadow-none">
                <a href="#inicio" class="rounded-md px-3 py-2 transition hover:text-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500">Inicio</a>
                <a href="#sobremi" class="rounded-md px-3 py-2 transition hover:text-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500">Sobre mí</a>
                <a href="#proyectos" class="rounded-md px-3 py-2 transition hover:text-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500">Proyectos</a>
                <a href="#contacto" class="rounded-md px-3 py-2 transition hover:text-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500">Contacto</a>
            </nav>
        </div>
    </header>

    <main class="pt-[5.5rem]">
        <!-- Sección Inicio -->
        <section id="inicio" class="section-anchor hero-banner min-h-screen flex items-center py-16"<?= $heroBannerStyle !== '' ? ' style="' . $heroBannerStyle . '"' : '' ?>>
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6 text-white drop-shadow-lg">Hola soy Jorge</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto text-sky-100 drop-shadow-md">
                    Estudiante de programación en la <strong class="text-white">Universidad Nacional de Salta</strong>, especializándome en <strong class="text-white">ciberseguridad y redes</strong>.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#proyectos" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">Mis proyectos</a>
                    <a href="#contacto" class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 font-bold py-3 px-6 rounded-lg transition">Contactame</a>
                </div>
            </div>
        </section>

        <!-- Sección Sobre Mí -->
        <section id="sobremi" class="section-anchor py-16 bg-white">
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
        <section id="proyectos" class="section-anchor py-16 bg-gray-50">
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
  <section id="contacto" class="section-anchor py-16 bg-white">
    <div class="container mx-auto px-4 max-w-2xl">
      <h2 class="text-3xl font-bold text-center mb-8 text-blue-900">Contacto</h2>

      <form id="form-contacto" class="space-y-6">
        <input type="hidden" name="token" value="<?= htmlspecialchars($formToken) ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 font-medium">Nombre:</label>
            <input type="text" name="nombre" required autocomplete="off"
              class="w-full px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block mb-2 font-medium">Apellido:</label>
            <input type="text" name="apellido" required autocomplete="off"
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
        <p> 2025 Castillo Chaustre Jorge Alberto - Todos los derechos reservados</p>
        <p class="mt-2"> Salta, Argentina</p>
        <p class="mt-2">Mail: jorcascero@gmail.com</p>
    </div>
</footer>

<script>
    (function () {
        const navToggle = document.getElementById('nav-toggle');
        const primaryNav = document.getElementById('primary-nav');

        if (navToggle && primaryNav) {
            const showNav = () => {
                primaryNav.classList.remove('hidden');
                primaryNav.classList.add('flex');
                navToggle.setAttribute('aria-expanded', 'true');
            };

            const hideNav = () => {
                primaryNav.classList.add('hidden');
                primaryNav.classList.remove('flex');
                navToggle.setAttribute('aria-expanded', 'false');
            };

            navToggle.addEventListener('click', () => {
                const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    hideNav();
                } else {
                    showNav();
                }
            });

            primaryNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        hideNav();
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    primaryNav.classList.add('flex');
                    primaryNav.classList.remove('hidden');
                    navToggle.setAttribute('aria-expanded', 'false');
                } else if (navToggle.getAttribute('aria-expanded') === 'false') {
                    primaryNav.classList.add('hidden');
                    primaryNav.classList.remove('flex');
                }
            });
        }

        const proyectosContainer = document.getElementById('proyectos-lista');
        if (!proyectosContainer) {
            return;
        }

        fetch('php/crud/listar_proyectos.php')
            .then(res => res.json())
            .then(data => {
                proyectosContainer.innerHTML = '';
                data.forEach(p => {
                    const card = document.createElement('div');
                    card.className = 'bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition';
                    card.innerHTML = `
                        <h3 class="text-xl font-bold mb-3 text-blue-800">${p.name_project}</h3>
                        <p class="mb-4">${p.description}</p>
                        <p class="mb-2"><strong class="text-blue-700">Estado:</strong> ${p.state == 1 ? 'Activo' : 'Inactivo'}</p>
                    `;
                    proyectosContainer.appendChild(card);
                });
            })
            .catch(err => console.error('Error cargando proyectos:', err));
    }());
</script>

<?php siteLayoutFooter(['scripts' => ['js/contacto.js']]); ?>
