<?php

namespace AttributesRouter\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    /**
     * @var array $parameters
     */
    private array $parameters = [];

    public function __construct(
        private string $path,
        private string $name = '',
        private string $method = 'GET',
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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $paramName
     * @param string $value
     */
    public function addParameter(string $paramName, string $value): void
    {
        $this->parameters[$paramName] = $value;
    }
}
