<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\GroundingWebSearch;

class Segment implements \JsonSerializable
{
    public function __construct(
        public readonly int $partIndex,
        public readonly int $startIndex,
        public readonly int $endIndex,
        public readonly string $text,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['partIndex'] ?? 0,
            $data['startIndex'] ?? 0,
            $data['endIndex'] ?? 0,
            $data['text'] ?? '',
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'partIndex' => $this->partIndex,
            'startIndex' => $this->startIndex,
            'endIndex' => $this->endIndex,
            'text' => $this->text,
        ];
    }
} 