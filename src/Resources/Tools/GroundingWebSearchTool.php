<?php

namespace GeminiAPI\Resources\Tools;

class GroundingWebSearchTool implements ToolInterface
{
    public function jsonSerialize(): array
    {
        return [
            'google_search' => (object) [],
        ];
    }
}
