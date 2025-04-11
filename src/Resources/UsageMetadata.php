<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

/**
 * @phpstan-type UsageMetadataResponse = array{
 *   promptTokenCount: int,
 *   candidatesTokenCount: int,
 *   totalTokenCount: int
 * }
 */
class UsageMetadata implements \JsonSerializable
{
    public function __construct(
        public readonly int $promptTokenCount,
        public readonly int $candidatesTokenCount,
        public readonly int $totalTokenCount,
    ) {
    }

    /**
     * @param UsageMetadataResponse $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            promptTokenCount: $data['promptTokenCount'],
            candidatesTokenCount: $data['candidatesTokenCount'],
            totalTokenCount: $data['totalTokenCount'],
        );
    }

    /**
     * @return UsageMetadataResponse
     */
    public function jsonSerialize(): array
    {
        return [
            'promptTokenCount' => $this->promptTokenCount,
            'candidatesTokenCount' => $this->candidatesTokenCount,
            'totalTokenCount' => $this->totalTokenCount,
        ];
    }
} 