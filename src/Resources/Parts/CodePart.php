<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use GeminiAPI\Responses\CodeResponsePart;
use GeminiAPI\Resources\Parts\PartInterface;
use Stringable;

use function json_encode;

class CodePart implements PartInterface, Stringable
{
    public function __construct(
        private readonly CodeResponsePart $codeResponsePart,
    ) {
    }

    public function getCodeResponsePart(): CodeResponsePart
    {
        return $this->codeResponsePart;
    }

    public function jsonSerialize(): array
    {
        return $this->codeResponsePart;
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
} 