<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionCallPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly array $args,
    ) {
    }

    /**
     * @return array{
     *     name: string,
     *     args: array,
     * }
     */
    public function jsonSerialize() : array
    {
        return ['name' => $this->name, 'args' => $this->args];
    }

    public function __toString() : string
    {
        return json_encode($this) ?: '';
    }
}
