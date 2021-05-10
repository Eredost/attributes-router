<?php

namespace AttributesRouter;

class Router
{
    public function __construct(
        private string $uri,
        private string $routes,
    ) {}
}
