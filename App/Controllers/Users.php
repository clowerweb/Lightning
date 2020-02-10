<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Users as Model;

/**
 * Users controller
 *
 * PHP version 7.2
 */
class Users extends Controller {
	public function before():void  {
		$this->requireAdmin();
	}

	public static function getAllAction(): void {
		header('Content-Type: application/json');
		echo json_encode(Model::getAll());
	}
}
