<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User as Model;

/**
 * User controller
 *
 * PHP version 7.2
 */
class User extends Controller {
	public function activateAction(): void {
		$this->requireAdmin();

		$user_id = $_POST['user_id'] ?? null;

		if($user_id) {
			$user_id = intval($user_id);

			if(Model::activate(null, $user_id)) {
				echo json_encode([
					'success' => true
				]);

				exit;
			}
		}

		echo json_encode([
			'success' => false
		]);
	}
}
