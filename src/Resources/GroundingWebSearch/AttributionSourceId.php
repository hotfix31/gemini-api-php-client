<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

/**
 * Identifier for the source of the attributed text.
 * 
 * This class represents the source identifier for attributed text in the Gemini API response.
 * It can contain different types of source identifiers:
 * - Web search query
 * - Retrieved chunk ID
 * - Semantic retriever chunk
 * - Grounding passage ID
 * 
 * @see https://ai.google.dev/api/rest/v1beta/GenerateContentResponse#attributionsourceid
 */
class AttributionSourceId implements \JsonSerializable
{
    /**
     * @param string|null $webSearchQuery Output only. Query used for web search.
     * @param string|null $retrievedChunkId Output only. ID of the retrieved chunk.
     * @param SemanticRetrieverChunk|null $semanticRetrieverChunk Output only. Identifier for a Chunk retrieved via Semantic Retriever.
     * @param GroundingPassageId|null $groundingPassage Output only. Identifier for a passage from grounding.
     */
    public function __construct(
        public readonly ?string $webSearchQuery = null,
        public readonly ?string $retrievedChunkId = null,
        public readonly ?SemanticRetrieverChunk $semanticRetrieverChunk = null,
        public readonly ?GroundingPassageId $groundingPassage = null,
    ) {
    }

    /**
     * Creates a new instance from an array of data.
     * 
     * @param array{
     *     webSearchQuery?: string|null,
     *     retrievedChunkId?: string|null,
     *     semanticRetrieverChunk?: array{source: string, chunk: string}|null,
     *     groundingPassage?: array{passageId: string, partIndex: int}|null
     * } $data The data to create the instance from
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['webSearchQuery'] ?? null,
            $data['retrievedChunkId'] ?? null,
            isset($data['semanticRetrieverChunk']) ? SemanticRetrieverChunk::fromArray($data['semanticRetrieverChunk']) : null,
            isset($data['groundingPassage']) ? GroundingPassageId::fromArray($data['groundingPassage']) : null,
        );
    }

    /**
     * Converts the instance to an array.
     * 
     * @return array{
     *     webSearchQuery?: string,
     *     retrievedChunkId?: string,
     *     semanticRetrieverChunk?: array{source: string, chunk: string},
     *     groundingPassage?: array{passageId: string, partIndex: int}
     * }
     */
    public function jsonSerialize(): array
    {
        $array = [];
        if ($this->webSearchQuery !== null) {
            $array['webSearchQuery'] = $this->webSearchQuery;
        }
        if ($this->retrievedChunkId !== null) {
            $array['retrievedChunkId'] = $this->retrievedChunkId;
        }
        if ($this->semanticRetrieverChunk !== null) {
            $array['semanticRetrieverChunk'] = $this->semanticRetrieverChunk;
        }
        if ($this->groundingPassage !== null) {
            $array['groundingPassage'] = $this->groundingPassage;
        }
        return $array;
    }
} 