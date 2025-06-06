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
 * Response from the GenerateContent API endpoint.
 * 
 * This class represents the response from the Gemini API when generating content.
 * It contains the generated candidates, feedback on the prompt, usage metadata, and model version.
 * 
 * @see https://ai.google.dev/api/rest/v1beta/models/generateContent
 * 
 * @phpstan-type CitationSource = array{
 *   startIndex?: int|null,
 *   endIndex?: int|null,
 *   uri?: string|null,
 *   license?: string|null
 * }
 * 
 * @phpstan-type CitationMetadata = array{
 *   citationSources: array<int, CitationSource>
 * }
 * 
 * @phpstan-type SafetyRating = array{
 *   category: string,
 *   probability: string,
 *   blocked: bool|null
 * }
 * 
 * @phpstan-type InlineData = array{
 *   mimeType: string,
 *   data: string
 * }
 * 
 * @phpstan-type FunctionCall = array{
 *   name: string,
 *   arguments: array<string, mixed>
 * }
 * 
 * @phpstan-type Part = array{
 *   text?: string,
 *   inlineData?: InlineData,
 *   functionCall?: FunctionCall
 * }
 * 
 * @phpstan-type Content = array{
 *   parts: array<int, Part>,
 *   role: string
 * }
 * 
 * @phpstan-type CandidateResponse = array{
 *   citationMetadata: CitationMetadata,
 *   safetyRatings: array<int, SafetyRating>,
 *   content: Content,
 *   finishReason: string,
 *   index: int
 * }
 */
class GenerateContentResponse
{
    use ArrayTypeValidator;

    /**
     * @param Candidate[] $candidates The generated content candidates
     * @param ?PromptFeedback $promptFeedback Feedback about the prompt
     * @param ?UsageMetadata $usageMetadata Metadata about token usage
     * @param string $modelVersion The version of the model used
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
     * Get all parts from the first candidate.
     * 
     * @return PartInterface[] Array of parts from the first candidate
     * @throws ValueError If no candidates are available or if there are multiple candidates
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

    /**
     * Get the text content from the first candidate.
     * 
     * @return string The text content
     * @throws ValueError If the response contains multiple parts or non-text parts
     */
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

    /**
     * Get the function call from the first candidate.
     * 
     * @return array{name: string, arguments: array<string, mixed>} The function call data
     * @throws ValueError If the response contains multiple parts or non-function call parts
     */
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
     * Creates a new instance from an array of data.
     * 
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
     * } $array The data to create the instance from
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
