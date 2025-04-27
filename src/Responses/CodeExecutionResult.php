<?php

namespace GeminiAPI\Responses;

class CodeExecutionResult implements \JsonSerializable
{
    public function __construct(
        private readonly string $outcome,
        private readonly string $output
    ) {
    }

    public function getOutcome(): string
    {
        return $this->outcome;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function jsonSerialize(): array
    {
        return [
            'outcome' => $this->outcome,
            'output' => $this->output,
        ];
    }
} 