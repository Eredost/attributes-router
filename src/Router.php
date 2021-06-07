<?php

namespace AttributesRouter;

use AttributesRouter\Attribute\Route;

class Router
{
    /**
     * Array that will contain in value, each of the routes defined in the controllers with the target class and
     * method and the name of the route as the key.
     *
     * @var array $routes
     */
    private array $routes = [];

    /**
     * Allows to define with a single call to the constructor, all the configuration necessary for the operation
     * of the router
     *
     * @param array  $controllers Classes containing Route attributes
     * @param string $baseURI Part of the URI to exclude
     *
     * @throws \ReflectionException when the controller does not exist
     */
    public function __construct(
        array $controllers = [],
        private string $baseURI = '',
    ) {
        if (!empty($controllers)) {
            $this->addRoutes($controllers);
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
     * Breaks down each of the controllers given as arguments to extract the routes attributes, instantiate them and
     * store them with the target class and method
     *
     * @param array $controllers
     *
     * @throws \ReflectionException when the controller does not exist
     */
    public function addRoutes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $reflectionMethod) {
                $routeAttributes = $reflectionMethod->getAttributes(Route::class);

                foreach ($routeAttributes as $routeAttribute) {
                    $route = $routeAttribute->newInstance();
                    $this->routes[$route->getName()] = [
                        'class'  => $reflectionMethod->class,
                        'method' => $reflectionMethod->name,
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

    /**
     * Generate a URL according to the name of the route
     *
     * @param string $name       The name of the route to generate
     * @param array  $parameters The parameters to provide if it is a dynamic route
     *
     * @return string
     */
    public function generateUrl(string $name, array $parameters = []): string
    {
        if (!isset($this->routes[$name])) {
            throw new \OutOfRangeException(sprintf('The route does not exist. Check that the given name "%s" is valid.', $name));
        }
        /** @var Route $route */
        $route = $this->routes[$name]['route'];
        $path = $route->getPath();

        if ($route->hasParams()) {
            $params = $route->fetchParams();

            // Checks that all parameters are provided
            if ($missingParameters = array_diff_key($params, $parameters)) {
                throw new \InvalidArgumentException(sprintf('The following parameters are missing for generating the route: %s', implode(', ', array_keys($missingParameters))));
            }

            // Compare each of the values provided with the regular expressions contained in the path
            foreach ($params as $paramName => $regex) {
                $regex = (!empty($regex) ? $regex : Route::DEFAULT_REGEX);

                if (!preg_match("/^$regex$/", $parameters[$paramName])) {
                    throw new \InvalidArgumentException(sprintf('The "%s" route parameter value given does not match the value expected', $paramName));
                }
                $path = preg_replace('/{' . $paramName . '(<.+>)?}/', $parameters[$paramName], $path);
            }
        }

        return $this->baseURI . $path;
    }
}
