<?php

namespace GeminiAPI\Resources;

use JsonSerializable;

class GoogleSearch implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            'google_search' => [],
        ];
    }
}
