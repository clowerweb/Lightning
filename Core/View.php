<?php

namespace Core;

use \App\Config;
use \App\Flash;

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
	 * @param array  $args - Associative array of data to display in the view (optional)
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
	 * @return string - the Twig template
	 */
	public static function getTemplate($template, $args = []) {
		static $twig = null;

		if($twig === null) {
			$opts    = [];
			$tpl_dir = Config::TEMPLATE_DIR;
			$loader  = new \Twig_Loader_Filesystem(dirname(__DIR__) . $tpl_dir);

			if(Config::TEMPLATE_CACHING) {
				$opts['cache'] = dirname(__DIR__) . $tpl_dir . '/cache';
			}

			$twig = new \Twig_Environment($loader, $opts);

			$twig->addGlobal('flash', Flash::getMessages());
			$twig->addGlobal('uri',   Utilities::getURI());
		}

		return $twig->render($template, $args);
	}

	/**
	 * Uses HTML Purifier to prevent XSS
	 * Useful if you want to add a front-end WYSIWYG editor for site visitors to use
	 * This should be placed on OUTPUT (such as with {{ twig.output|raw }})
	 *
	 * @param string $html - the HTML to purify
	 *
	 * @return string
	 */
	public static function purifyOutput($html) {
		$config   = \HTMLPurifier_Config::createDefault();
		$purifier = new \HTMLPurifier($config);

		return $purifier->purify($html);
	}
}