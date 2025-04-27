<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

use GeminiAPI\Traits\ArrayTypeValidator;

class GroundingSupport implements \JsonSerializable
{
    use ArrayTypeValidator;

    public function __construct(
        public readonly array $groundingChunkIndices,
        public readonly array $confidenceScores,
        public readonly Segment $segment,
    ) {
        $this->ensureArrayOfInt($this->groundingChunkIndices);
        $this->ensureArrayOfFloat($this->confidenceScores);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['groundingChunkIndices'] ?? [],
            $data['confidenceScores'] ?? [],
            Segment::fromArray($data['segment'] ?? []),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'groundingChunkIndices' => $this->groundingChunkIndices,
            'confidenceScores' => $this->confidenceScores,
            'segment' => $this->segment,
        ];
    }
} 