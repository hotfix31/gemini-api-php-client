<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Traits\ArrayTypeValidator;

class Tool
{
    use ArrayTypeValidator;

    /**
     * @param array[] $functionDeclarations
     */
    public function __construct(
        public array $functionDeclarations,
    ) {
    }

    public function addFunctionCall(string $name, $description, $parameters): self
    {
        $this->functionDeclarations[] = new FunctionCall($name, $description, $parameters);

        return $this;
    }

    public static function functionCall(
        string $name,
        string|null $description,
        array $parameters
    ): self {
        return new self(
            [
                new FunctionCall($name, $description, $parameters),
            ],
        );
    }


    /**
     * @param array{
     *     function_declarations: array<int, array{name?: string, description?: string, parameters?: array}>,
     *     role: string,
     * } $tools
     * @return self
     */
    public static function fromArray(array $tools): self
    {
        $functionDeclarations = [];
        foreach ($tools['function_declarations'] as $function) {
            if (! empty($function['name'])) {
                $functionDeclarations[] = new FunctionCall(
                    $function['name'],
                    $function['description'] ?? null,
                    $function['parameters'],
                );
            }
        }

        if (isset($tools['google_search_retrieval'])) {
            $functionDeclarations[] = new GoogleSearch($tools['google_search_retrieval']);
        }

        return new self(
            $functionDeclarations,
        );
    }
}
