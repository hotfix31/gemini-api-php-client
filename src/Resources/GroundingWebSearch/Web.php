<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

class Web implements \JsonSerializable
{
    public function __construct(
        public readonly string $uri,
        public readonly string $title,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['uri'] ?? '',
            $data['title'] ?? '',
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'uri' => $this->uri,
            'title' => $this->title,
        ];
    }
} 