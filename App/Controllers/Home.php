<?php

namespace App\Controllers;

use Core\Utilities;
use Core\View;
use Core\Controller;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Controller {
	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction() {
		// Example of using HTML Purifier to prevent XSS
		$html = "<h3>Test</h3><script>alert();</script>";
		$html = Utilities::purifyOutput($html);

		View::renderTemplate('Home/index.twig', [
			'html' => $html
		]);
	}
}