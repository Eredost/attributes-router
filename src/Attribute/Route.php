<?php

namespace AttributesRouter\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    public const DEFAULT_REGEX = '[\w\-]+';

    /**
     * @var array $parameters Contains all URI parameters with name as key and corresponding value
     */
    private array $parameters = [];

    public function __construct(
        private string $path,
        private string $name = '',
        private array $methods = ['GET'],
    ) {
        if (empty($this->name)) {
            $this->name = $this->path;
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $paramName Name of the parameter defined in the path
     * @param string $value     Corresponding value of the parameter
     */
    public function addParameter(string $paramName, string $value): void
    {
        $this->parameters[$paramName] = $value;
    }

    /**
     * Checks the presence of parameters in the path of the route
     *
     * @return bool
     */
    public function hasParams(): bool
    {
        return preg_match('/{([\w\-%]+)(<(.+)>)?}/', $this->path);
    }

    /**
     * Retrieves in key of the array, the names of the parameters as well as the regular expression (if there is one)
     * in value
     *
     * @return array
     */
    public function fetchParams(): array
    {
        preg_match_all('/{([\w\-%]+)(?:<(.+)>)?}/', $this->getPath(), $params);

        return array_combine($params[1], $params[2]);
    }
}
