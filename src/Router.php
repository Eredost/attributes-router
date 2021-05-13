<?php

namespace AttributesRouter;

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
}
