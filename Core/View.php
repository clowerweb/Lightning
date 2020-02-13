<?php

namespace Core;

use \Exception;
use App\Flash;
use App\Models\Settings;
use App\Auth;
use stdClass;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * View class
 *
 * PHP version 7.2
 */
class View {
    /**
     * Render a template
     *
     * @param string $template - The template file
     * @param array  $args - Associative array of data to display in the view (optional)
     *
     * @throws Exception
     *
     * @return void
     */
    public static function renderTemplate($template, $args = []) {
        echo static::getTemplate($template, $args);
    }

    /**
     * Get the contents of a view template using Twig
     *
     * @param string $template - The template file
     * @param array  $args - Associative array of data to display in the view (optional)
     *
     * @throws Exception
     *
     * @return string - the Twig template
     */
    public static function getTemplate($template, $args = []) {
        static $twig = null;

        if($twig === null) {
            $opts       = [];
            $settings   = Settings::getSettings();
            $domain     = Utilities::getDomain();
            $dev        = strtolower(getenv('ENVIRONMENT')) !== 'prod';
            $assets_url = $dev ? 'http://localhost:8080' : $domain . '/assets';
            $tpl_dir    = '/App/Views/';
            $loader     = new FilesystemLoader(dirname(__DIR__) . $tpl_dir);
            $uri_path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments   = explode('/', $uri_path);
            $body_class = '';

            $opts['debug'] = $dev;

            foreach ($segments as $segment) {
                if(Utilities::isEmpty($segments[1])) {
                    $body_class = 'home';
                }

                if(!Utilities::isEmpty($segment)) {
                    $body_class .= $segment . ' ';
                }
            }

            if(strtolower(getenv('TEMPLATE_CACHING')) === 'true') {
                $opts['cache'] = dirname(__DIR__, 1) . getenv('CACHE_DIRECTORY');
            }

            $twig = new Environment($loader, $opts);

            $twig->addGlobal('flash_messages', Flash::getMessages());
            $twig->addGlobal('uri', Utilities::getURI());
            $twig->addGlobal('base_url', $domain);
            $twig->addGlobal('assets_url', $assets_url);
            $twig->addGlobal('settings', $settings);
            $twig->addGlobal('body_class', $body_class);
            $twig->addGlobal('current_year', Date('Y'));
            $twig->addGlobal('site_name', $settings['site_name']);
            $twig->addGlobal('site_tagline', $settings['site_tagline']);
            $twig->addGlobal('dev', $dev);
			$twig->addGlobal('curruser', Auth::getUser() ?: new stdClass);

            if($dev) {
                $twig->addExtension(new DebugExtension());
            }
        }

        return $twig->render($template, $args);
    }
}
