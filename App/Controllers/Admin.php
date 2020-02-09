<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\Settings;
use \Exception;
use App\Auth;
use App\Flash;
use Core\Utilities;
use Core\Controller;
use Core\View;

/**
 * Admin controller
 *
 * PHP version 7.2
 */
class Admin extends Controller {
	private $user;

	public function before(): void {
		$this->user = Auth::getUser();
		$this->requireAdmin();
	}

	/**
	 * Render the admin index
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function indexAction(): void {
		View::renderTemplate('Admin/index.twig', [
			'user' => $this->user
		]);
	}

	public function saveSettingsAction() {
		$data = $_POST ?? null;

		if($data) {
			if(Settings::saveSettings($data)) {
				Flash::addMessage('Settings saved.', Flash::SUCCESS);
			} else {
				Flash::addMessage('An unknown error occurred.', Flash::ERROR);
			}
		}

		Utilities::redirect('/admin');
	}
}
