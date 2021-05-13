<?php

namespace AttributesRouter;

use AttributesRouter\Attribute\Route;

class Router
{
    /**
     * Allows to define with a single call to the constructor, all the configuration necessary for the operation
     * of the router
     *
     * @param array  $controllers Classes containing Route attributes
     * @param string $baseURI     Part of the URI to exclude
     */
    public function __construct(
        private array $controllers = [],
        private string $baseURI = '',
    ) {}

    /**
     * Define the base URI in order to exclude it in the route correspondence, useful when the project is called from a
     * subfolder
     *
     * @param string $baseURI Part of the URI to exclude
     */
    public function setBaseURI(string $baseURI): void
    {
        $this->baseURI = $baseURI;
    }

    /**
     * Add the controllers sent as arguments to those already stored
     *
     * @param array $controllers Classes containing Route attributes
     */
    public function addControllers(array $controllers): void
    {
        $this->controllers = array_merge($this->controllers, $controllers);
    }

    /**
     * Iterate over all the attributes of the controllers in order to find the first one corresponding to the request.
     * If a match is found then an array is returned with the class, method and parameters, otherwise null is returned
     *
     * @todo support parameters in the url
     *
     * @return string[]|null
     * @throws \ReflectionException if the controller does not exist
     */
    public function match(): ?array
    {
        $request = $_SERVER['REQUEST_URI'];

        if (!empty($this->baseURI)) {
            $baseURI = preg_quote($this->baseURI, '/');
            $request = preg_replace("/^{$baseURI}/", '', $request);
        }

        foreach ($this->controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $routeAttributes = $method->getAttributes(Route::class);

                foreach ($routeAttributes as $attribute) {
                    $route = $attribute->newInstance();

                    if ($route->getPath() === $request
                        && $route->getMethod() === $_SERVER['REQUEST_METHOD']) {
                        return [
                            'class'  => $method->class,
                            'method' => $method->name,
                            'params' => '', // TODO: return url parameters
                        ];
                    }
                }
            }
        }

        return null;
    }
}
