<?php

declare(strict_types=1);

use JsonSerializable;

class FunctionCall implements JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly array $arguments = [],
    ) {
    }

    /**
     * @param array{
     *     name: string,
     *     description: string,
     *     arguments: array,
     * } $array
     * @return self
     */
    public static function fromArray(array $array) : self
    {
        return new self($array['name'], $array['description'], $array['arguments']);
    }

    public function jsonSerialize() : array
    {
        return [
            'function_declarations' => [
                'name' => $this->name,
                'description' => $this->description,
                'arguments' => json_encode($this->arguments),
            ],
        ];
    }

    public function __toString() : string
    {
        return json_encode($this) ?: '';
    }
}