<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Traits\ArrayTypeValidator;

class Tool
{
    use ArrayTypeValidator;

    /**
     * @param array[] $function_declarations
     */
    public function __construct(
        public array $function_declarations,
    ) {
    }

    public function addFunctionCall(string $name, $description, $parameters) : self
    {
        $this->function_declarations[] = new FunctionCall($name, $description, $parameters);

        return $this;
    }

    public static function functionCall(
        string $name,
        string $description,
        array $parameters
    ) : self {
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
    public static function fromArray(array $tools) : self
    {
        $function_declarations = [];
        foreach ($tools['function_declarations'] as $function) {
            if (! empty($part['name'])) {
                $function_declarations[] = new FunctionCall($function['name'], $function['description'], $function['arguments']);
            }
        }

        return new self(
            $function_declarations,
        );
    }
}
