<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Tools;

use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Traits\ArrayTypeValidator;

class FunctionCallingTool implements ToolInterface
{
    use ArrayTypeValidator;

    /**
     * @param array[] $functionDeclarations
     */
    public function __construct(
        public readonly array $functionDeclarations,
        public readonly bool $groundingWebSearch = false,
        public readonly bool $codeExecution = false,
    ) {
        $this->ensureArrayOfType($functionDeclarations, FunctionCall::class);
    }

    public function addFunctionCall(string $name, $description, $parameters): self
    {
        $currentFunctionDeclarations = $this->functionDeclarations;
        $currentFunctionDeclarations[] = new FunctionCall($name, $description, $parameters);

        return new self($currentFunctionDeclarations, $this->groundingWebSearch, $this->codeExecution);
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

    public function jsonSerialize(): array
    {
        return [
            'function_declarations' => $this->functionDeclarations,
        ];
    }
}
