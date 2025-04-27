<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use GeminiAPI\Enums\FinishReason;
use GeminiAPI\Enums\Role;
use GeminiAPI\Traits\ArrayTypeValidator;
use UnexpectedValueException;
use GeminiAPI\Resources\GroundingWebSearch\GroundingAttribution;
use GeminiAPI\Resources\GroundingWebSearch\GroundingMetadata;

/**
 * @phpstan-import-type CandidateResponse from \GeminiAPI\Responses\GenerateContentResponse
 */
class Candidate
{
    use ArrayTypeValidator;

    /**
     * @param Content $content
     * @param FinishReason $finishReason
     * @param CitationMetadata $citationMetadata
     * @param SafetyRating[] $safetyRatings
     * @param int $tokenCount
     * @param int $index
     * @param GroundingAttribution[] $groundingAttributions
     * @param GroundingMetadata $groundingMetadata
     * @param float $avgLogprobs
     * @param LogprobsResult $logprobsResult
     */
    public function __construct(
        public readonly Content $content,
        public readonly FinishReason $finishReason,
        public readonly CitationMetadata $citationMetadata,
        public readonly array $safetyRatings,
        public readonly int $tokenCount,
        public readonly int $index,
        public readonly array $groundingAttributions = [],
        public readonly ?GroundingMetadata $groundingMetadata = null,
        public readonly ?float $avgLogprobs = null,
        public readonly ?LogprobsResult $logprobsResult = null,
    ) {
        if ($tokenCount < 0) {
            throw new UnexpectedValueException('tokenCount cannot be negative');
        }

        if ($index < 0) {
            throw new UnexpectedValueException('index cannot be negative');
        }

        $this->ensureArrayOfType($safetyRatings, SafetyRating::class);
        $this->ensureArrayOfType($groundingAttributions, GroundingAttribution::class);
    }

    /**
     * @param CandidateResponse $candidate
     * @return self
     */
    public static function fromArray(array $candidate): self
    {
        $content = isset($candidate['content'])
            ? Content::fromArray($candidate['content'])
            : Content::text('', Role::Model);

        $finishReason = isset($candidate['finishReason'])
            ? FinishReason::from($candidate['finishReason'])
            : FinishReason::OTHER;

        $safetyRatings = array_map(
            static fn (array $rating): SafetyRating => SafetyRating::fromArray($rating),
            $candidate['safetyRatings'] ?? [],
        );

        $citationMetadata = isset($candidate['citationMetadata'])
            ? CitationMetadata::fromArray($candidate['citationMetadata'])
            : new CitationMetadata();

        $groundingAttributions = array_map(
            static fn (array $attribution): GroundingAttribution => GroundingAttribution::fromArray($attribution),
            $candidate['groundingAttributions'] ?? [],
        );

        $groundingMetadata = isset($candidate['groundingMetadata'])
            ? GroundingMetadata::fromArray($candidate['groundingMetadata'])
            : null;

        $logprobsResult = isset($candidate['logprobsResult'])
            ? LogprobsResult::fromArray($candidate['logprobsResult'])
            : null;

        return new self(
            $content,
            $finishReason,
            $citationMetadata,
            $safetyRatings,
            $candidate['tokenCount'] ?? 0,
            $candidate['index'] ?? 0,
            $groundingAttributions,
            $groundingMetadata,
            $candidate['avgLogprobs'] ?? null,
            $logprobsResult,
        );
    }
}
