<?php

namespace AttributesRouter\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    /**
     * Default regular expression when none is defined in the parameter
     */
    public const DEFAULT_REGEX = '[\w\-]+';

    /**
     * @var array $parameters Keeps the parameters cached with the associated regex
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
        if (empty($this->parameters)) {
            preg_match_all('/{([\w\-%]+)(?:<(.+)>)?}/', $this->getPath(), $params);
            $this->parameters = array_combine($params[1], $params[2]);
        }

        return $this->parameters;
    }
}
