<?php

declare(strict_types=1);

namespace GeminiAPI\Responses;

use GeminiAPI\Traits\ArrayTypeValidator;
use GeminiAPI\Resources\Candidate;
use GeminiAPI\Resources\UsageMetadata;
use GeminiAPI\Resources\Parts\FunctionCallPart;
use GeminiAPI\Resources\Parts\PartInterface;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\PromptFeedback;
use ValueError;

/**
 * @phpstan-type CandidateResponse = array{
 *   citationMetadata: array{
 *     citationSources: array<int, array{
 *       startIndex?: int|null,
 *       endIndex?: int|null,
 *       uri?: string|null,
 *       license?: string|null
 *     }>
 *   },
 *   safetyRatings: array<int, array{
 *     category: string,
 *     probability: string,
 *     blocked: bool|null
 *   }>,
 *   content: array{
 *     parts: array<int, array{
 *       text?: string,
 *       inlineData?: array{
 *         mimeType: string,
 *         data: string
 *       },
 *       functionCall?: array{
 *         name: string,
 *         arguments: array<string, mixed>
 *       },
 *     }>,
 *     role: string
 *   },
 *   finishReason: string,
 *   index: int
 *  }
 */
class GenerateContentResponse
{
    use ArrayTypeValidator;

    /**
     * @param Candidate[] $candidates
     * @param ?PromptFeedback $promptFeedback
     * @param ?UsageMetadata $usageMetadata
     * @param string $modelVersion
     */
    public function __construct(
        public readonly array $candidates,
        public readonly ?PromptFeedback $promptFeedback = null,
        public readonly ?UsageMetadata $usageMetadata = null,
        public readonly string $modelVersion = '',
    ) {
        $this->ensureArrayOfType($candidates, Candidate::class);
    }

    /**
     * @return PartInterface[]
     */
    public function parts(): array
    {
        if (empty($this->candidates)) {
            throw new ValueError(
                'The `GenerateContentResponse::parts()` quick accessor '.
                'only works for a single candidate, but none were returned. '.
                'Check the `GenerateContentResponse::$promptFeedback` to see if the prompt was blocked.'
            );
        }

        if (count($this->candidates) > 1) {
            throw new ValueError(
                'The `GenerateContentResponse::parts()` quick accessor '.
                'only works with a single candidate. '.
                'With multiple candidates use GenerateContentResponse.candidates[index].text'
            );
        }

        return $this->candidates[0]->content->parts;
    }

    public function text(): string
    {
        $parts = $this->parts();

        if (count($parts) > 1 || !$parts[0] instanceof TextPart) {
            throw new ValueError(
                'The `GenerateContentResponse::text()` quick accessor '.
                'only works for simple (single-`Part`) text responses. '.
                'This response contains multiple `Parts`. '.
                'Use the `GenerateContentResponse::parts()` accessor '.
                'or the full `GenerateContentResponse.candidates[index].content.parts` lookup instead'
            );
        }

        return $parts[0]->text;
    }

    public function functionCall(): array
    {
        $parts = $this->parts();

        if (count($parts) > 1) {
            throw new ValueError(
                'The `GenerateContentResponse::functionCall()` quick accessor ' .
                'only works for simple (single-`Part`) text responses. ' .
                'This response contains multiple `Parts`. ' .
                'Use the `GenerateContentResponse::parts()` accessor ' .
                'or the full `GenerateContentResponse.candidates[index].content.parts` lookup instead'
            );
        }

        if (!$parts[0] instanceof FunctionCallPart) {
            throw new ValueError(
                'The `GenerateContentResponse::functionCall()` quick accessor ' .
                'only works for simple (single-`Part`) text responses. ' .
                'This response contains multiple `Parts`. ' .
                'Use the `GenerateContentResponse::parts()` accessor ' .
                'or the full `GenerateContentResponse.candidates[index].content.parts` lookup instead'
            );
        }

        return $parts[0]->jsonSerialize()['functionCall'];
    }

    /**
     * @param array{
     *  promptFeedback: array{
     *   blockReason: string|null,
     *   safetyRatings?: array<int, array{category: string, probability: string, blocked: bool|null}>,
     *  },
     *  candidates: array<int, CandidateResponse>,
     *  usageMetadata: array{
     *    promptTokenCount: int,
     *    candidatesTokenCount: int,
     *    totalTokenCount: int,
     *    promptTokensDetails: list<array{
     *      modality: string,
     *      tokenCount: int,
     *    }>
     *    thoughtsTokenCount: int,
     *  },
     *  modelVersion: string,
     * } $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        $promptFeedback = null;
        if (!empty($array['promptFeedback'])) {
            $promptFeedback = PromptFeedback::fromArray($array['promptFeedback']);
        }

        $candidates = array_map(
            static fn (array $candidate): Candidate => Candidate::fromArray($candidate),
            $array['candidates'] ?? [],
        );

        $usageMetadata = null;
        if (!empty($array['usageMetadata'])) {
            $usageMetadata = UsageMetadata::fromArray($array['usageMetadata']);
        }

        return new self(
            $candidates,
            $promptFeedback,
            $usageMetadata,
            $array['modelVersion'] ?? '',
        );
    }
}
