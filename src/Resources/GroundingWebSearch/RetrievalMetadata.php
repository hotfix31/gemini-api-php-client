<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

class RetrievalMetadata implements \JsonSerializable
{
    public function __construct(
        public readonly ?float $googleSearchDynamicRetrievalScore = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['googleSearchDynamicRetrievalScore']) ? (float)$data['googleSearchDynamicRetrievalScore'] : null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'googleSearchDynamicRetrievalScore' => $this->googleSearchDynamicRetrievalScore,
        ];
    }
} 