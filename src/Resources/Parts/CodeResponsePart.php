<?php

namespace GeminiAPI\Resources\Parts;

use GeminiAPI\Responses\ExecutableCode;
use GeminiAPI\Responses\CodeExecutionResult;

class CodeResponsePart implements PartInterface
{
    public function __construct(
        public readonly ?string $text = null,
        public readonly ?ExecutableCode $executableCode = null,
        public readonly ?CodeExecutionResult $codeExecutionResult = null
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->text !== null) {
            $result['text'] = $this->text;
        }

        if ($this->executableCode !== null) {
            $result['executable_code'] = $this->executableCode;
        }

        if ($this->codeExecutionResult !== null) {
            $result['code_execution_result'] = $this->codeExecutionResult;
        }

        return $result;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'] ?? null,
            executableCode: isset($data['executable_code']) 
                ? new ExecutableCode(
                    $data['executable_code']['language'],
                    $data['executable_code']['code']
                ) 
                : null,
            codeExecutionResult: isset($data['code_execution_result'])
                ? new CodeExecutionResult(
                    $data['code_execution_result']['outcome'],
                    $data['code_execution_result']['output']
                )
                : null
        );
    }
} 