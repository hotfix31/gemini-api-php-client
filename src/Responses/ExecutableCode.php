<?php

namespace GeminiAPI\Responses;

class ExecutableCode implements \JsonSerializable
{
    public function __construct(
        private readonly string $language,
        private readonly string $code
    ) {
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize(): array
    {
        return [
            'language' => $this->language,
            'code' => $this->code,
        ];
    }
} 