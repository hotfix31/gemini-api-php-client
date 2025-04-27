<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

use GeminiAPI\Traits\ArrayTypeValidator;

class GroundingMetadata implements \JsonSerializable
{
    use ArrayTypeValidator;

    public function __construct(
        /** @var GroundingChunk[] */
        public readonly array $groundingChunks,
        /** @var GroundingSupport[] */
        public readonly array $groundingSupports,
        /** @var string[] */
        public readonly array $webSearchQueries,
        public readonly ?SearchEntryPoint $searchEntryPoint,
        public readonly ?RetrievalMetadata $retrievalMetadata,
    ) {
        $this->ensureArrayOfType($this->groundingChunks, GroundingChunk::class);
        $this->ensureArrayOfType($this->groundingSupports, GroundingSupport::class);
        $this->ensureArrayOfString($this->webSearchQueries);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            array_map(
                static fn (array $chunk): GroundingChunk => GroundingChunk::fromArray($chunk),
                $data['groundingChunks'] ?? [],
            ),
            array_map(
                static fn (array $support): GroundingSupport => GroundingSupport::fromArray($support),
                $data['groundingSupports'] ?? [],
            ),
            $data['webSearchQueries'] ?? [],
            isset($data['searchEntryPoint']) ? SearchEntryPoint::fromArray($data['searchEntryPoint']) : null,
            isset($data['retrievalMetadata']) ? RetrievalMetadata::fromArray($data['retrievalMetadata']) : null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'groundingChunks' => $this->groundingChunks,
            'groundingSupports' => $this->groundingSupports,
            'webSearchQueries' => $this->webSearchQueries,
            'searchEntryPoint' => $this->searchEntryPoint,
            'retrievalMetadata' => $this->retrievalMetadata,
        ];
    }
} 