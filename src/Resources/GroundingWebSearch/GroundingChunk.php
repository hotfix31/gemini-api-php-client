<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

class GroundingChunk implements \JsonSerializable
{
    public function __construct(
        public readonly ?Web $web,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['web']) ? Web::fromArray($data['web']) : null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'web' => $this->web,
        ];
    }
} 