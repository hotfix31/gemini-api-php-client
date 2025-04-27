<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

use GeminiAPI\Resources\Content;

/**
 * Attribution for a source that contributed to an answer.
 */
class GroundingAttribution implements \JsonSerializable
{
    /**
     * @param AttributionSourceId $sourceId Output only. Identifier for the source contributing to this attribution.
     * @param Content $content Grounding source content that makes up this attribution.
     */
    public function __construct(
        public readonly AttributionSourceId $sourceId,
        public readonly Content $content,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            AttributionSourceId::fromArray($data['sourceId']),
            Content::fromArray($data['content']),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'sourceId' => $this->sourceId,
            'content' => $this->content,
        ];
    }
} 