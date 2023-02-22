<?php

namespace Lightning;

use Exception;

/**
 * Router class for Lightning 3
 *
 * Has methods for adding routes and dispatching the current URL to the appropriate
 * controller and action.
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
class Router {
    // Associative array of routes
    protected $routes = [];
    // Parameters from the matched route
    protected $params = [];

    /**
     * Adds a new route to the router.
     *
     * @param string $route The URL route pattern.
     * @param array $params An array of route parameters.
     *
     * @return void
     */
    public function add(string $route, array $params = []): void {
        // Escape forward slashes in the route pattern
        $route = preg_replace('/\//', '\\/', $route);

        // Convert named parameters in the route pattern to named capture groups
        $route = preg_replace('/{(\w+)}/', '(?P<\1>\w+)', $route);

        // Convert named parameters with a custom regex pattern to named capture groups with that pattern
        $route = preg_replace('/{(\w+):(\w+)}/', '(?P<\1>\2)', $route);

        // Add start and end anchors to the regular expression pattern
        $route = '/^' . $route . '$/i';

        // Add the route and its associated parameters to the router's route table
        $this->routes[$route] = $params;
    }

    /**
     * Dispatches the current URL to the appropriate controller and action.
     *
     * @param string $url The URL to dispatch.
     *
     * @return void
     * @throws Exception If the route is not found or the controller or method is not accessible.
     *
     */
    public function dispatch(string $url): void {
        // Remove any query string variables from the URL
        $url = Utilities::removeQueryStringVars($url);

        // Check if the URL matches any routes
        if (!$this->match($url)) {
            throw new Exception("Route not found for '$url'.", 404);
        }

        // Build the fully-qualified controller class name and action method name
        $controllerClass = $this->getNamespace() .
            Utilities::convertToStudlyCaps($this->params['controller']);

        // Check if the controller class exists; if not, use the Home controller and index action.
        // 404s should be handled in the SPA
        if (!class_exists($controllerClass)) {
            $controller = new ($this->getNamespace() . 'Home')();
            $action = 'index';
        } else {
            $action = ucfirst($this->params['action']);

            // Create a new instance of the controller and call the action method
            $controller = new $controllerClass($this->params);
            if (!method_exists($controller, 'action' . $action)) {
                $controller = new ($this->getNamespace() . 'Home')();
                $action = 'index';
            }
        }

        $controller->$action();
    }

    /**
     * Attempts to match the given URL to a route in the router.
     *
     * @param string $url The URL to match against the router's routes.
     *
     * @return bool True if the URL matches a route in the router, false otherwise.
     */
    private function match(string $url): bool {
        // Iterate over each route in the router
        foreach ($this->routes as $route => $params) {
            // Try to match the URL to the current route using a regular expression
            if (preg_match($route, $url, $matches)) {
                // If the URL matches the route, extract any named parameters from the match and add them to the route parameters
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                // Set the matched route parameters as the current request parameters and return true
                $this->params = $params;

                return true;
            }
        }

        // If no route was found that matches the URL, return false
        return false;
    }

    /**
     * Returns the namespace for the current controller.
     *
     * @return string The namespace for the current controller.
     */
    private function getNamespace(): string {
        // Get the namespace for the current controller from the router parameters
        return 'App\Controllers\\' . ($this->params['namespace'] ?? '');
    }
}
