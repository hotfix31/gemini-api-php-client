<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

class ObjectFunctionParameter extends FunctionParameter
{
    public function __construct(
        private readonly string|null $description,
        /**
         * @var array<string, FunctionParameter>
         */
        private readonly array $properties,
        /**
         * @var list<string>
         */
        private readonly array $required,
    ) {
        parent::__construct('object', $description);
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            ['properties' => $this->properties],
            ['required' => $this->required],
        );
    }
} 