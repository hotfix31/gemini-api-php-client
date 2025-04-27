<?php

namespace GeminiAPI\Resources\Tools;

class CodeExecutionTool implements ToolInterface
{
    public function jsonSerialize(): array
    {
        return [
            'code_execution' => (object) [],
        ];
    }
}
