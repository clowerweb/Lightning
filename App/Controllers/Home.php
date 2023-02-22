<?php

namespace App\Controllers;

use Lightning\Controller;
use Lightning\Lightning;

/**
 * Home controller for Lightning 3
 *
 * PHP version 8.2
 *
 * @since 3.0.0
 * @package App\Controllers
 */
class Home extends Controller {
    /**
     * Show the index page
     *
     * @return void
     */
    public function actionIndex(): void {
        Lightning::render('Home');
    }
}
