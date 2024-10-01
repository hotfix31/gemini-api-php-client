<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionCallPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly array $functionCalls,
    ) {
    }

    /**
     * @return array{
     *     functionCalls: array,
     * }
     */
    public function jsonSerialize() : array
    {
        return ['name' => $this->functionCalls['name'], 'args' => $this->functionCalls['args']];
    }

    public function __toString() : string
    {
        return json_encode($this) ?: '';
    }
}
