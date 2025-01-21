<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use JsonSerializable;

class GoogleSearch implements JsonSerializable
{
    public function __construct(
        public readonly array $tool,
    ) {
    }

    /**
     * @param array{
     *     tool: array
     * } $array
     * @return self
     */
    public static function fromArray(array $array) : self
    {
        return new self($array['tool']);
    }

    public function jsonSerialize() : array
    {
        return [
            'tool' => $this->tool
        ];
    }
}