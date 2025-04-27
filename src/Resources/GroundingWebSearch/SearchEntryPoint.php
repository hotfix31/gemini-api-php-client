<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

class SearchEntryPoint implements \JsonSerializable
{
    public function __construct(
        public readonly ?string $renderedContent,
        public readonly ?string $sdkBlob,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['renderedContent'] ?? null,
            $data['sdkBlob'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'renderedContent' => $this->renderedContent,
            'sdkBlob' => $this->sdkBlob,
        ];
    }
} 