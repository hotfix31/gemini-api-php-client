<?php

declare(strict_types=1);

namespace GeminiAPI\Requests;

use GeminiAPI\Resources\Content;
use GeminiAPI\Traits\ArrayTypeValidator;
use GeminiAPI\Traits\ModelNameToString;
use JsonSerializable;

use function json_encode;

class CountTokensRequest implements JsonSerializable, RequestInterface
{
    use ArrayTypeValidator;
    use ModelNameToString;

    /**
     * @param Content[] $contents
     */
    public function __construct(
        public readonly string $modelName,
        public readonly array $contents,
        public readonly array $safetySettings = [],
        public readonly ?GenerationConfig $generationConfig = null,
        public readonly ?Content $systemInstruction = null,
        public readonly array $tools = [],
    ) {
        $this->ensureArrayOfType($this->contents, Content::class);
    }

    public function getOperation(): string
    {
        return "{$this->modelNameToString($this->modelName)}:countTokens";
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getHttpPayload(): string
    {
        return (string) $this;
    }

    /**
     * @return array{
     *     model: string,
     *     contents: Content[],
     * }
     */
    public function jsonSerialize(): array
    {
        $arr = [
            'model' => $this->modelNameToString($this->modelName),
            'contents' => $this->contents,
        ];

        if (!empty($this->safetySettings)) {
            $arr['safetySettings'] = $this->safetySettings;
        }

        if ($this->generationConfig) {
            $arr['generationConfig'] = $this->generationConfig;
        }

        if ($this->systemInstruction) {
            $arr['systemInstruction'] = $this->systemInstruction;
        }

        if (!empty($this->tools)) {
            $arr['tools'] = $this->tools;
        }

        return $arr;
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
