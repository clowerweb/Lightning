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

	public function approveAction(): void {
		$this->requireAdmin();

		$user_id = $_POST['user_id']     ?? null;

		if($user_id) {
			$user_id     = intval($user_id);
			$is_approved = intval($_POST['is_approved']);

			if(Model::approve($user_id, $is_approved)) {
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

	public function deactivateAction(): void {
		$this->requireAdmin();

		$user_id = $_POST['user_id'] ?? null;

		if($user_id) {
			$user_id = intval($user_id);

			if(Model::deactivate($user_id)) {
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

	public function reactivateAction(): void {
		$this->requireAdmin();

		$user_id = $_POST['user_id'] ?? null;

		if($user_id) {
			$user_id = intval($user_id);

			if(Model::reactivate($user_id)) {
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
