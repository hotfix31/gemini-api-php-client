<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionCallPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly array $functionCall,
    ) {
    }

    /**
     * @return array{
     *     functionCall: array,
     * }
     */
    public function jsonSerialize() : array
    {
        return ['functionCall' => ['name' => $this->functionCall['name'], 'args' => $this->functionCall['args']]];
    }

    public function __toString() : string
    {
        return json_encode($this) ?: '';
    }
}
