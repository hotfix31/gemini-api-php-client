<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

class FunctionParameter implements \JsonSerializable
{
    public function __construct(
        private readonly string $type,
        private readonly string|null $description,
        /**
         * @var list<string>|null
         */
        private readonly ?array $enum = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $data = [
            'type' => $this->type,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->enum !== null) {
            $data['enum'] = $this->enum;
        }

        return $data;
    }

    public static function string(string|null $description, ?array $enum = null): self
    {
        return new self('string', $description, $enum);
    }

    public static function integer(string|null $description, ?array $enum = null): self
    {
        return new self('integer', $description, $enum);
    }

    public static function boolean(string|null $description): self
    {
        return new self('boolean', $description);
    }
} 