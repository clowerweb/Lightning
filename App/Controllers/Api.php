<?php

namespace App\Controllers;

use Lightning\Controller;

/**
 * API Controller for Lightning 3
 *
 * PHP version 8.2
 *
 * @since 3.0.0
 * @package App\Controllers
 */
class Api extends Controller {
    public function actionIndex(): void {
        echo 'Lightning is a super fast and simple Vue SPA & PHP MVC API framework from Clowerweb!';
    }

    public function actionAboutMsg(): void {
        echo 'This comes from the API for the About page!';
    }
}
