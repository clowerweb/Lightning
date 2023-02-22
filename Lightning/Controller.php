<?php

namespace Lightning;

use BadMethodCallException;
use Exception;

/**
 * Controller class for Lightning 3
 *
 * Has methods for calling a controller method and before/after filters
 *
 * PHP version 8.2
 *
 * @since 1.0.0
 * @package Lightning
 */
abstract class Controller {
    /**
     * __call magic method
     *
     * @param string $name The name of the method to call
     * @param array  $args Arguments passed to the method
     *
     * @throws BadMethodCallException
     * @return void
     */
    public function __call(string $name, array $args): void {
        $method = 'action' . ucfirst($name);

        if (!is_callable([$this, $method])) {
            throw new BadMethodCallException(sprintf('Call to undefined method %s::%s()', static::class, $method));
        }

        $before = method_exists($this, 'before') ? $this->before() : null;
        $result = $this->{$method}(...$args);
        $after  = method_exists($this, 'after') ? $this->after() : null;
    }

    /**
     * Before filter - called before an action method
     *
     * @return void
     */
    protected function before(): void {}

    /**
     * After filter - called after an action method
     *
     * @return void
     */
    protected function after(): void {}
}
