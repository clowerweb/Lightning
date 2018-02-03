<?php

declare(strict_types = 1);

namespace Core;

use \Twig_Environment;
use \Twig_Loader_Filesystem;
use App\Config;
use App\Flash;

/**
 * View class
 *
 * PHP version 7.0
 */
class View {
	/**
	 * Render a template
	 *
	 * @param string $template - The template file
	 * @param array  $args     - Associative array of data to display in the view (optional)
	 *
	 * @return void
	 */
	public static function renderTemplate(string $template, array $args = []) {
		echo static::getTemplate($template, $args);
	}

	/**
	 * Get the contents of a view template using Twig
	 *
	 * @param string $template - The template file
	 * @param array  $args     - Associative array of data to display in the view (optional)
	 *
	 * @return string - the Twig template
	 */
	public static function getTemplate(string $template, array $args = []) : string {
		static $twig = null;

		if($twig === null) {
			$opts       = [];
			$tpl_dir    = Config::TEMPLATE_DIR;
			$loader     = new Twig_Loader_Filesystem(dirname(__DIR__) . $tpl_dir);
			$url        = ltrim(Utilities::getURI(), '/');
			$body_class = str_replace('/', ' ', $url);
			$body_class = $body_class ? $body_class : 'home';
			$canonical  = rtrim(Utilities::getURL(), '/');

			if(Config::TEMPLATE_CACHING) {
				$opts['cache'] = Utilities::getAbsRoot() . Config::CACHE_DIRECTORY;
			}

			$twig = new Twig_Environment($loader, $opts);

			$twig->addGlobal('flash', Flash::getMessages());
			$twig->addGlobal('uri',   Utilities::getURI());
			$twig->addGlobal('template_dir', str_replace('/public', '', $tpl_dir));
			$twig->addGlobal('body_class', $body_class);
			$twig->addGlobal('current_year', date('Y'));
			$twig->addGlobal('canonical', $canonical);
		}

		return $twig->render($template, $args);
	}
}
