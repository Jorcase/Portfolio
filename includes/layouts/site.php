<?php
// includes/layouts/site.php

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
     * Devuelve la ruta relativa (respecto a la página actual) con cache-busting si aplica.
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

if (!function_exists('siteLayoutHeader')) {
    /**
     * Imprime el encabezado HTML común del sitio público.
     */
    function siteLayoutHeader(array $options = []): void
    {
        $title = $options['title'] ?? 'Portfolio';
        $bodyClass = $options['bodyClass'] ?? 'bg-slate-50 text-slate-900 font-body antialiased';
        $includeTailwind = $options['includeTailwind'] ?? true;
        $stylesheets = $options['stylesheets'] ?? [];
        $tailwindHref = $options['tailwindHref'] ?? 'public/css/tailwind.css';
        $headExtras = $options['headExtras'] ?? '';

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
    }
}

if (!function_exists('siteLayoutFooter')) {
    /**
     * Cierra la estructura HTML del sitio público.
     */
    function siteLayoutFooter(array $options = []): void
    {
        $scripts = $options['scripts'] ?? [];

        foreach ($scripts as $script) {
            echo '    <script src="' . htmlspecialchars($script) . '"></script>' . "\n";
        }

        echo "</body>\n";
        echo "</html>\n";
    }
}
