<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

/**
 * Identifier for a part within a GroundingPassage.
 */
class GroundingPassageId implements \JsonSerializable
{
    /**
     * @param string $passageId Output only. ID of the passage matching the GenerateAnswerRequest's GroundingPassage.id.
     * @param int $partIndex Output only. Index of the part within the GenerateAnswerRequest's GroundingPassage.content.
     */
    public function __construct(
        public readonly string $passageId,
        public readonly int $partIndex,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['passageId'],
            $data['partIndex'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'passageId' => $this->passageId,
            'partIndex' => $this->partIndex,
        ];
    }
} 