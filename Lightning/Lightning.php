<?php

namespace Lightning;

/**
 * Lightning class for Lightning 3
 *
 * PHP version 8.2
 *
 * @since 3.0.0
 * @package Lightning
 */
abstract class Lightning {
    public static function render($file) {
        $file = dirname(__DIR__) . '/App/Views/' . $file . '.php';

        require_once $file;
    }
}
