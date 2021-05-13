<?php

namespace AttributesRouter\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
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
}
