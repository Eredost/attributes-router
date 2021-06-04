<?php

namespace AttributesRouter;

use AttributesRouter\Attribute\Route;

class Router
{
    /**
     * @var array $routes
     */
    private array $routes = [];

    /**
     * Allows to define with a single call to the constructor, all the configuration necessary for the operation
     * of the router
     *
     * @param array  $controllers Classes containing Route attributes
     * @param string $baseURI     Part of the URI to exclude
     */
    public function __construct(
        array $controllers = [],
        private string $baseURI = '',
    ) {
        if (!empty($controllers)) {
            $this->fetchRouteAttributes($controllers);
        }
    }

    /**
     * Define the base URI in order to exclude it in the route correspondence, useful when the project is called from a
     * sub-folder
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
        $this->fetchRouteAttributes($controllers);
    }

    /**
     * Decomposes each of the controllers given in argument and gets all the Route attributes
     */
    private function fetchRouteAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $routeAttributes = $method->getAttributes(Route::class);

                foreach ($routeAttributes as $routeAttribute) {
                    $route = $routeAttribute->newInstance();
                    $this->routes[$route->getName()] = [
                        'class'  => $method->class,
                        'method' => $method->name,
                        'route'  => $route,
                    ];
                }
            }
        }
    }

    /**
     * Iterate over all the attributes of the controllers in order to find the first one corresponding to the request.
     * If a match is found then an array is returned with the class, method and parameters, otherwise null is returned
     *
     * @return string[]|null
     */
    public function match(): ?array
    {
        $request = $_SERVER['REQUEST_URI'];

        if (!empty($this->baseURI)) {
            $baseURI = preg_quote($this->baseURI, '/');
            $request = preg_replace("/^{$baseURI}/", '', $request);
        }
        $request = (empty($request) ? '/': $request);

        foreach ($this->routes as $route) {
            if ($this->matchRequest($request, $route['route'])) {
                return [
                    'class'  => $route['class'],
                    'method' => $route['method'],
                    'params' => $route['route']->getParameters(),
                ];
            }
        }

        return null;
    }

    /**
     * Check if the user's request matches the given route
     *
     * @param string $request Request URI
     * @param Route  $route   Route attribute
     *
     * @return bool
     */
    private function matchRequest(string $request, Route $route): bool
    {
        $requestArray = explode('/', $request);
        $pathArray = explode('/', $route->getPath());

        if (!(count($requestArray) === count($pathArray))
            || !(in_array($_SERVER['REQUEST_METHOD'], $route->getMethods(), true))) {
            return false;
        }
        unset($pathArray[0]);

        foreach ($pathArray as $index => $urlPart) {
            if (isset($requestArray[$index])) {
                if (str_starts_with($urlPart, '{')) {
                    $params = explode(' ', preg_replace('/{([\w\-%]+)(<(.+)>)?}/', '$1 $3', $urlPart));
                    $paramName = $params[0];
                    $paramRegExp = (empty($params[1]) ? '[\w\-]+': $params[1]);

                    if (preg_match('/^' . $paramRegExp . '$/', $requestArray[$index])) {
                        $route->addParameter($paramName, $requestArray[$index]);

                        continue;
                    }
                } elseif ($urlPart === $requestArray[$index]) {
                    continue;
                }
            }

            return false;
        }

        return true;
    }

    public function generateUrl(string $name, array $parameters = []): string
    {
        if (!isset($this->routes[$name])) {
            throw new \OutOfRangeException('The route does not exist. Check that the given name is valid.');
        }
        /** @var Route $route */
        $route = $this->routes[$name]['route'];

        if ($route->hasParams()) {
            $params = $route->fetchParams();
        }

        return $this->baseURI . $route->getPath();
    }
}
