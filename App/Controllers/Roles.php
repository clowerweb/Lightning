<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Roles as Model;

/**
 * Roles controller
 *
 * PHP version 7.2
 */
class Roles extends Controller {
	public function getRolesAction() {
		return Model::getRoles();
	}

	public function updateAction() {
		$this->requireAdmin();

		$user_id = $_POST['user_id'] ?? null;
		$role_id = $_POST['role_id'] ?? null;

		if(Model::update(intval($user_id), intval($role_id))) {
			echo json_encode([
				'success' => true
			]);

			exit;
		}

		echo json_encode([
			'success' => false
		]);
	}
}
