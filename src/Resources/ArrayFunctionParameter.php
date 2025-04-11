<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

class ArrayFunctionParameter extends FunctionParameter
{
    public function __construct(
        private readonly string|null $description,
        private readonly FunctionParameter $items,
    ) {
        parent::__construct('array', $description);
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            ['items' => $this->items],
        );
    }
} 