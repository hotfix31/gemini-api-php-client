<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class GoogleSearchPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly array $googleSearch,
    ) {
    }

    /**
     * @return array{
     *     googleSearch: array,
     * }
     */
    public function jsonSerialize(): array
    {
        return ['name' => $this->googleSearch['name'], 'args' => $this->googleSearch['args']];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
