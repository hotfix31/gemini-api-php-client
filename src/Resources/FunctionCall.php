<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use JsonSerializable;

class FunctionCall implements JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly array $parameters = [],
    ) {
    }

    /**
     * @param array{
     *     name: string,
     *     description: string,
     *     parameters: array,
     * } $array
     * @return self
     */
    public static function fromArray(array $array) : self
    {
        return new self($array['name'], $array['description'], $array['parameters']);
    }

    public function jsonSerialize() : array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => $this->parameters,
        ];
    }
}