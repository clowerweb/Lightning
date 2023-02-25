<?php

namespace Lightning;

/**
 * ViteHelper class for Lightning 3
 *
 * PHP version 8.2
 *
 * @since 3.0.0
 * @package App
 */
class ViteHelper {
    /**
     * Returns a string with preloaded JavaScript imports and CSS tags for the specified Vite entry.
     *
     * @param string $entry The Vite entry for which to generate preloaded JavaScript imports and CSS tags.
     *
     * @return void
     */
    public static function vite(string $entry): void {
        echo self::jsPreloadImports($entry) . self::cssTag($entry);
    }

    /**
     * Determines if the given entrypoint file is being served by the Vite dev server or not.
     *
     * @param string $entry The entrypoint file
     *
     * @return bool True if the file is being served by Vite dev server, false otherwise
     */
    private static function isDev(string $entry): bool {
        static $exists = null;

        if ($exists !== null) {
            return $exists;
        }

        $handle = curl_init($_ENV['VITE_HOST'] . '/' . $entry);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_exec($handle);
        $error = curl_errno($handle);
        curl_close($handle);

        return $exists = !$error;
    }

    /**
     * Returns a script tag for a JavaScript entrypoint file.
     *
     * @param string $entry The entrypoint file
     *
     * @return void
     */
    public static function jsTag(string $entry): void {
        $url = self::isDev($entry) ? $_ENV['VITE_HOST'] . '/src/' . $entry : self::assetUrl($entry);

        if (!$url) {
            return;
        }

        echo '<script type="module" crossorigin src="' . $url . '"></script>';
    }

    /**
     * Generate HTML code with CSS tags for a given Vite entry.
     *
     * @param string $entry The Vite entry point.
     *
     * @return string The generated HTML code.
     */
    private static function cssTag(string $entry): string {
        static $manifest = null;

        // Check if the app is running in development mode.
        if (self::isDev($entry)) {
            return '';
        }

        // Load the manifest file if it hasn't been loaded yet.
        if ($manifest === null) {
            $manifest = self::getManifest();
        }

        // Generate HTML code with links to CSS files.
        $tags = '';
        if (isset($manifest[ $entry ]['css'])) {
            foreach ($manifest[ $entry ]['css'] as $file) {
                $tags .= '<link rel="stylesheet" href="/dist/' . $file . '">';
            }
        }

        // Return the generated HTML code.
        return $tags;
    }

    /**
     * Returns the manifest.json file as an array.
     *
     * @return array The manifest.json contents as an array
     */
    private static function getManifest(): array {
        static $manifest;

        if (!isset($manifest)) {
            $manifest = json_decode(file_get_contents(__DIR__ .
                '/dist/manifest.json'), true);
        }

        return $manifest;
    }

    /**
     * Returns the URL to an asset file.
     *
     * @param string $entry The entrypoint file
     *
     * @return string The URL to the asset file
     */
    private static function assetUrl(string $entry): string {
        static $manifest = null;
        $manifestFile    = __DIR__ . '/dist/manifest.json';

        if ($manifest === null) {
            $manifestTime     = filemtime($manifestFile);
            $manifest         = json_decode(file_get_contents($manifestFile), true);
            $manifest['time'] = $manifestTime;
        } else {
            $currentManifestTime = filemtime($manifestFile);
            if ($currentManifestTime > $manifest['time']) {
                $manifest  = json_decode(file_get_contents($manifestFile), true);
                $manifest['time'] = $currentManifestTime;
            }
        }

        return isset($manifest[ $entry ])
            ? '/dist/' . $manifest[ $entry ]['file']
            : '';
    }

    /**
     * Returns an array of URLs to the CSS files associated with the given entrypoint file.
     *
     * @param string $entry The entrypoint file
     *
     * @return array An array of CSS URLs
     */
    private static function cssUrls(string $entry): array {
        $manifest = self::getManifest();

        return array_map(function ($file) {
            return '/dist/' . $file;
        }, $manifest[ $entry ]['css'] ?? []);
    }

    /**
     * Returns an array of URLs to the JavaScript modules imported by the given entrypoint file.
     *
     * @param string $entry The entrypoint file
     * @return array An array of JavaScript module URLs
     */
    private static function importsUrls(string $entry): array {
        $urls     = []; // Initialize an empty array to store the URLs
        $manifest = self::getManifest(); // Load the manifest.json file

        if (!empty($manifest[$entry]['imports'])) { // If the entrypoint file has imports
            foreach ($manifest[$entry]['imports'] as $imports) { // Loop through each import
                $urls[] = '/dist/' . $manifest[$imports]['file']; // Add the URL to the array
            }
        }

        return $urls; // Return the array of URLs
    }

    /**
     * Generate HTML code with preloaded JavaScript imports for a given Vite entry.
     *
     * @param string $entry The Vite entry point.
     *
     * @return string The generated HTML code.
     */
    private static function jsPreloadImports(string $entry): string {
        // Check if the app is running in development mode.
        if (self::isDev($entry)) {
            return '';
        }

        // Generate HTML code with links to preloaded JavaScript imports.
        $urls = self::importsUrls($entry);
        if (empty($urls)) {
            return '';
        }

        return '<link rel="modulepreload" href="' .
            implode('" /><link rel="modulepreload" href="', $urls) . '">';
    }
}
