<?php

$config = [
    'routes' => [
        // You shouldn't edit these
        '{controller}' => ['action' => 'index'],
        '{controller}/{action}' => [],
        '{controller}/{action}/{token:[\da-f]+}' => [],
        // Add your custom routes here
        '' => ['controller' => 'Home', 'action' => 'index'],
    ]
];
