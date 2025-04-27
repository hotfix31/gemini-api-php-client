<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use function json_encode;
use Stringable;

class TextPart implements PartInterface, Stringable
{
    public function __construct(
        public readonly string $text,
    ) {
    }

    /**
     * @return array{
     *     text: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return ['text' => $this->text];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
