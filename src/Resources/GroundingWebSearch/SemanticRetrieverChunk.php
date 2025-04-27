<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

/**
 * Identifier for a Chunk retrieved via Semantic Retriever specified in the GenerateAnswerRequest using SemanticRetrieverConfig.
 */
class SemanticRetrieverChunk implements \JsonSerializable
{
    /**
     * @param string $source Output only. Name of the source matching the request's SemanticRetrieverConfig.source. Example: corpora/123 or corpora/123/documents/abc
     * @param string $chunk Output only. Name of the Chunk containing the attributed text. Example: corpora/123/documents/abc/chunks/xyz
     */
    public function __construct(
        public readonly string $source,
        public readonly string $chunk,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['source'],
            $data['chunk'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'source' => $this->source,
            'chunk' => $this->chunk,
        ];
    }
} 