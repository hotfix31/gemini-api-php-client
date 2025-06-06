<?php

declare(strict_types=1);

namespace GeminiAPI;

use GeminiAPI\Traits\ArrayTypeValidator;
use JsonSerializable;
use UnexpectedValueException;

class GenerationConfig implements JsonSerializable
{
    use ArrayTypeValidator;

    private const MAX_THINKING_BUDGET = 24576;

    /** @var array{
     *     candidateCount?: int,
     *     stopSequences?: string[],
     *     maxOutputTokens?: int,
     *     temperature?: float,
     *     topP?: float,
     *     topK?: int,
     *     responseMimeType?: string,
     *     responseSchema?: array{
     *         type: string,
     *         schema: array<string, mixed>,
     *     }[],
     *     thinkingConfig?: array{
     *         thinkingBudget: int,
     *     },
     *     responseModalities?: string[],
     * }
     */
    private array $config;

    public function withCandidateCount(int $candidateCount): self
    {
        if ($candidateCount < 0) {
            throw new UnexpectedValueException('Candidate count is negative');
        }

        $clone = clone $this;
        $clone->config['candidateCount'] = $candidateCount;

        return $clone;
    }

    /**
     * @param string[] $stopSequences
     * @return $this
     */
    public function withStopSequences(array $stopSequences): self
    {
        $this->ensureArrayOfString($stopSequences);

        $clone = clone $this;
        $clone->config['stopSequences'] = $stopSequences;

        return $clone;
    }

    public function withMaxOutputTokens(int $maxOutputTokens): self
    {
        if ($maxOutputTokens < 0) {
            throw new UnexpectedValueException('Max output tokens is negative');
        }

        $clone = clone $this;
        $clone->config['maxOutputTokens'] = $maxOutputTokens;

        return $clone;
    }

    public function withTemperature(float $temperature): self
    {
        if ($temperature < 0.0 || $temperature > 1.0) {
            throw new UnexpectedValueException('Temperature is negative or more than 1');
        }

        $clone = clone $this;
        $clone->config['temperature'] = $temperature;

        return $clone;
    }

    public function withTopP(float $topP): self
    {
        if ($topP < 0.0) {
            throw new UnexpectedValueException('Top-p is negative');
        }

        $clone = clone $this;
        $clone->config['topP'] = $topP;

        return $clone;
    }

    public function withTopK(int $topK): self
    {
        if ($topK < 0) {
            throw new UnexpectedValueException('Top-k is negative');
        }

        $clone = clone $this;
        $clone->config['topK'] = $topK;

        return $clone;
    }

    public function withResponseMimeType(string $responseMimeType): self
    {
        $clone = clone $this;
        $clone->config['responseMimeType'] = $responseMimeType;

        return $clone;
    }

    /**
     * @param array{
     *     type: string,
     *     schema: array<string, mixed>,
     * }[] $responseSchema
     */
    public function withResponseSchema(array $responseSchema): self
    {
        $clone = clone $this;
        $clone->config['responseSchema'] = $responseSchema;

        return $clone;
    }

    public function withThinkingConfig(int $thinkingBudget): self
    {
        if ($thinkingBudget < 0) {
            throw new UnexpectedValueException('Thinking budget is negative');
        }

        if ($thinkingBudget > self::MAX_THINKING_BUDGET) {
            throw new UnexpectedValueException('Thinking budget is more than ' . self::MAX_THINKING_BUDGET);
        }

        $clone = clone $this;
        $clone->config['thinkingConfig'] = ['thinkingBudget' => $thinkingBudget];

        return $clone;
    }

    /**
     * @param string[] $responseModalities
     * @return $this
     */
    public function withResponseModalities(array $responseModalities): self
    {
        $this->ensureArrayOfString($responseModalities);

        $clone = clone $this;
        $clone->config['responseModalities'] = $responseModalities;

        return $clone;
    }

    /**
     * @return array{
     *      candidateCount?: int,
     *      stopSequences?: string[],
     *      maxOutputTokens?: int,
     *      temperature?: float,
     *      topP?: float,
     *      topK?: int,
     *      responseMimeType?: string,
     *      responseSchema?: array{
     *          type: string,
     *          schema: array<string, mixed>,
     *      }[],
     *     thinkingConfig?: array{
     *         thinkingBudget: int,
     *     },
     *     responseModalities?: string[],
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->config;
    }
}
