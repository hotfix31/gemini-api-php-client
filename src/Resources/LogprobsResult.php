<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

class LogprobsResult
{
    public function __construct(
        public readonly array $tokens,
        public readonly array $tokenLogprobs,
        public readonly array $topLogprobs,
        public readonly array $textOffset,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['tokens'] ?? [],
            $data['tokenLogprobs'] ?? [],
            $data['topLogprobs'] ?? [],
            $data['textOffset'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'tokens' => $this->tokens,
            'tokenLogprobs' => $this->tokenLogprobs,
            'topLogprobs' => $this->topLogprobs,
            'textOffset' => $this->textOffset,
        ];
    }
} 