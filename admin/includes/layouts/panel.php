<?php
// admin/includes/layouts/panel.php

if (!function_exists('portfolioProjectRoot')) {
    /**
     * Localiza el directorio raíz del proyecto (donde viven composer.json/package.json).
     */
    function portfolioProjectRoot(): ?string
    {
        static $cachedRoot = null;

        if ($cachedRoot !== null) {
            return $cachedRoot;
        }

        $current = __DIR__;

        while (!empty($current)) {
            if (file_exists($current . '/composer.json') || file_exists($current . '/package.json')) {
                return $cachedRoot = realpath($current) ?: null;
            }

            $parent = dirname($current);
            if ($parent === $current) {
                break;
            }

            $current = $parent;
        }

        return $cachedRoot;
    }
}

if (!function_exists('portfolioResolveAssetPath')) {
    /**
     * Obtiene la ruta relativa correcta hacia un recurso partiendo del script actual.
     */
    function portfolioResolveAssetPath(string $asset): string
    {
        if ($asset === '') {
            return $asset;
        }

        $firstChar = $asset[0];
        $startsWithParent = strncmp($asset, '../', 3) === 0;
        $startsWithCurrent = strncmp($asset, './', 2) === 0;
        if ($firstChar === '/' || $startsWithParent || $startsWithCurrent || preg_match('#^(?:https?:)?//#i', $asset)) {
            return $asset;
        }

        $projectRoot = portfolioProjectRoot();
        $scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';

        if ($projectRoot && $scriptFilename && file_exists($scriptFilename)) {
            $scriptDir = dirname(realpath($scriptFilename));
            if ($scriptDir !== false) {
                $projectRootNormalized = rtrim(str_replace('\\', '/', $projectRoot), '/');
                $scriptDirNormalized = rtrim(str_replace('\\', '/', $scriptDir), '/');

                if (strncmp($scriptDirNormalized, $projectRootNormalized, strlen($projectRootNormalized)) === 0) {
                    $relativeDir = trim(substr($scriptDirNormalized, strlen($projectRootNormalized)), '/');

                    if ($relativeDir === '') {
                        return $asset;
                    }

                    $segments = array_values(array_filter(explode('/', $relativeDir), static fn ($segment) => $segment !== ''));
                    $depth = count($segments);

                    if ($depth > 0) {
                        return str_repeat('../', $depth) . ltrim($asset, '/');
                    }
                }
            }
        }

        return $asset;
    }
}

if (!function_exists('portfolioAssetHref')) {
    /**
     * Devuelve la ruta relativa (respecto al script actual) agregando un query param de versión si es local.
     */
    function portfolioAssetHref(string $asset): string
    {
        $resolved = portfolioResolveAssetPath($asset);
        $projectRoot = portfolioProjectRoot();

        if ($projectRoot) {
            $normalized = ltrim(str_replace('\\', '/', $asset), '/');
            $absolutePath = $projectRoot . '/' . $normalized;

            if (is_file($absolutePath)) {
                $version = (string) filemtime($absolutePath);
                $separator = strpos($resolved, '?') !== false ? '&' : '?';
                return $resolved . $separator . 'v=' . $version;
            }
        }

        return $resolved;
    }
}

if (!function_exists('adminLayoutHeader')) {
    /**
     * Renderiza la cabecera HTML común del panel administrador.
     */
    function adminLayoutHeader(array $options = []): void
    {
        $title = $options['title'] ?? 'Panel de Administración';
        $subtitle = $options['subtitle'] ?? '';
        $actions = $options['actions'] ?? [];
        $bodyClass = $options['bodyClass'] ?? 'bg-slate-950 text-slate-100 font-body min-h-screen antialiased';
        $mainWrapperClass = $options['mainWrapperClass'] ?? 'max-w-6xl mx-auto px-6 lg:px-12 py-12 space-y-10';
        $headerWrapperClass = $options['headerWrapperClass'] ?? 'max-w-6xl mx-auto px-6 lg:px-12 py-8';
        $headExtras = $options['headExtras'] ?? '';
        $showHeader = $options['showHeader'] ?? true;
        $includeTailwind = $options['includeTailwind'] ?? true;
        $stylesheets = $options['stylesheets'] ?? [];
        $tailwindHref = $options['tailwindHref'] ?? 'public/css/tailwind.css';

        echo "<!DOCTYPE html>\n";
        echo '<html lang="es">' . "\n";
        echo "<head>\n";
        echo '    <meta charset="UTF-8">' . "\n";
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        echo '    <title>' . htmlspecialchars($title) . '</title>' . "\n";
        echo '    <link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">' . "\n";

        if ($includeTailwind) {
            $resolvedTailwindHref = portfolioAssetHref($tailwindHref);
            if (!in_array($resolvedTailwindHref, $stylesheets, true)) {
                array_unshift($stylesheets, $resolvedTailwindHref);
            }
        }

        foreach ($stylesheets as $stylesheet) {
            $resolvedStylesheet = portfolioAssetHref($stylesheet);
            echo '    <link rel="stylesheet" href="' . htmlspecialchars($resolvedStylesheet) . '">' . "\n";
        }
        if (!empty($headExtras)) {
            echo "    {$headExtras}\n";
        }
        echo "</head>\n";
        echo '<body class="' . htmlspecialchars($bodyClass) . '">' . "\n";

        if ($showHeader) {
            echo '    <header class="border-b border-panelMuted/60 bg-panel/95 backdrop-blur">' . "\n";
            echo '        <div class="' . htmlspecialchars($headerWrapperClass) . ' flex flex-col gap-6 md:flex-row md:items-center md:justify-between">' . "\n";
            echo '            <div class="space-y-2">' . "\n";
            echo '                <p class="text-xs uppercase tracking-[0.35em] text-panelAccent/70">Dashboard</p>' . "\n";
            echo '                <h1 class="font-heading text-3xl font-semibold text-white">' . htmlspecialchars($title) . '</h1>' . "\n";
            if (!empty($subtitle)) {
                echo '                <p class="text-sm text-white/70">' . htmlspecialchars($subtitle) . '</p>' . "\n";
            }
            echo '            </div>' . "\n";

            if (!empty($actions)) {
                echo '            <div class="flex flex-wrap items-center gap-3">' . "\n";
                foreach ($actions as $action) {
                    $href = $action['href'] ?? '#';
                    $label = $action['label'] ?? '';
                    $variant = $action['variant'] ?? 'primary';
                    $target = $action['target'] ?? '';

                    $baseClass = 'inline-flex items-center gap-2 rounded-full px-5 py-2 text-sm font-semibold transition';
                    if ($variant === 'secondary') {
                        $class = $baseClass . ' border border-white/20 bg-transparent text-white/80 hover:border-panelAccent hover:text-panelAccent';
                    } elseif ($variant === 'danger') {
                        $class = $baseClass . ' bg-panelDanger/90 text-white hover:bg-panelDanger';
                    } else {
                        $class = $baseClass . ' bg-panelAccent text-panel font-semibold hover:bg-panelAccent/80';
                    }

                    $targetAttr = !empty($target) ? ' target="' . htmlspecialchars($target) . '"' : '';

                    echo '                <a href="' . htmlspecialchars($href) . '" class="' . htmlspecialchars($class) . '"' . $targetAttr . '>' . htmlspecialchars($label) . '</a>' . "\n";
                }
                echo '            </div>' . "\n";
            }

            echo '        </div>' . "\n";
            echo '    </header>' . "\n";
        }

        echo '    <main class="' . htmlspecialchars($mainWrapperClass) . '">' . "\n";
    }
}

if (!function_exists('adminLayoutFooter')) {
    /**
     * Cierra el layout del panel administrador.
     */
    function adminLayoutFooter(): void
    {
        echo "    </main>\n";
        echo "</body>\n";
        echo "</html>\n";
    }
}
