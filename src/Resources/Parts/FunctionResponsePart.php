<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use Stringable;

use function json_encode;

class FunctionResponsePart implements PartInterface, Stringable
{
    public function __construct(
        public readonly array $functionResponse,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->functionResponse['name'],
            'response' => $this->functionResponse['response'],
        ];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
