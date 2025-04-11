<?php

namespace GeminiAPI\Resources;

use JsonSerializable;

class FunctionCall implements JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        /**
         * @var array<string, FunctionParameter>
         */
        public readonly array $parameters = [],
        /**
         * @var list<string>
         */
        public readonly array $required = [],
    ) {
    }

    /**
     * @param array{
     *     name: string,
     *     description?: string|null,
     *     parameters?: array<string, FunctionParameter>,
     *     required?: list<string>,
     * } $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['name'],
            $array['description'] ?? null,
            $array['parameters'] ?? [],
            $array['required'] ?? [],
        );
    }

    /**
     * @return array{
     *     name: string,
     *     description: string,
     *     parameters: array<string, FunctionParameter>,
     *     required: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => new ObjectFunctionParameter(null, $this->parameters, $this->required),
        ];
    }
}
