<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionResponsePart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly array $functionResponse,
    ) {
    }

    /**
     * @return array{
     *     functionResponse: array,
     * }
     */
    public function jsonSerialize() : array
    {
        return [
            'functionResponse' => 
            [
                'name' => $this->functionResponse['name'],
                'response' => [
                    'name' => $this->functionResponse['name'],
                    'content' => $this->functionResponse['response'],
                ],
            ]
        ];
    }

    public function __toString() : string
    {
        return json_encode($this) ?: '';
    }
}
