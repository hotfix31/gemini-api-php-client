<?php

declare(strict_types=1);

namespace GeminiAPI\Resources;

use GeminiAPI\Enums\MimeType;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Parts\FilePart;
use GeminiAPI\Traits\ArrayTypeValidator;
use GeminiAPI\Resources\Parts\ImagePart;
use GeminiAPI\Resources\Parts\PartInterface;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\Parts\FunctionCallPart;
use GeminiAPI\Resources\Parts\FunctionResponsePart;
use GeminiAPI\Resources\Parts\CodeResponsePart;

class Content implements \JsonSerializable
{
    use ArrayTypeValidator;

    /**
     * @param PartInterface[] $parts
     * @param Role $role
     */
    public function __construct(
        public array $parts,
        public readonly Role $role,
    ) {
        $this->ensureArrayOfType($parts, PartInterface::class);
    }

    public function addText(string $text): self
    {
        $this->parts[] = new TextPart($text);

        return $this;
    }

    public function addImage(MimeType $mimeType, string $image): self
    {
        $this->parts[] = new ImagePart($mimeType, $image);

        return $this;
    }

    public function addFile(MimeType $mimeType, string $file): self
    {
        $this->parts[] = new FilePart($mimeType, $file);

        return $this;
    }

    public static function text(
        string $text,
        Role $role = Role::User,
    ): self {
        return new self(
            [
                new TextPart($text),
            ],
            $role,
        );
    }

    public static function image(
        MimeType $mimeType,
        string $image,
        Role $role = Role::User
    ): self {
        return new self(
            [
                new ImagePart($mimeType, $image),
            ],
            $role,
        );
    }

    public static function file(
        MimeType $mimeType,
        string $file,
        Role $role = Role::User
    ): self {
        return new self(
            [
                new FilePart($mimeType, $file),
            ],
            $role,
        );
    }

    public static function textAndImage(
        string $text,
        MimeType $mimeType,
        string $image,
        Role $role = Role::User,
    ): self {
        return new self(
            [
                new TextPart($text),
                new ImagePart($mimeType, $image),
            ],
            $role,
        );
    }

    public static function textAndFile(
        string $text,
        MimeType $mimeType,
        string $file,
        Role $role = Role::User,
    ): self {
        return new self(
            [
                new TextPart($text),
                new FilePart($mimeType, $file),
            ],
            $role,
        );
    }

    public static function functionCall(
        string $name,
        array $args,
    ): self {
        return new self(
            [
                new FunctionCallPart($name, $args),
            ],
            Role::Model,
        );
    }

    public static function functionResponse(
        string $name,
        array $args,
    ): self {
        return new self(
            [
                new FunctionResponsePart(['name' => $name, 'response' => $args]),
            ],
            Role::Model,
        );
    }

    /**
     * @param array{
     *     parts: array<int, array{
     *         text?: string,
     *         inlineData?: array{mimeType: string, data: string},
     *         functionCall?: array{name: string, args: array},
     *         functionResponse?: array{name: string, response: array},
     *         executable_code?: array{language: string, code: string},
     *         code_execution_result?: array{outcome: string, output: string}
     *     }>,
     *     role: string,
     * } $content
     * @return self
     */
    public static function fromArray(array $content): self
    {
        $parts = [];
        foreach ($content['parts'] as $part) {
            if (!empty($part['executable_code']) || !empty($part['code_execution_result'])) {
                $parts[] = CodeResponsePart::fromArray($part);
                continue;
            }

            if (!empty($part['text'])) {
                $parts[] = new TextPart($part['text']);
                continue;
            }

            if (!empty($part['functionCall'])) {
                $parts[] = new FunctionCallPart($part['functionCall']['name'], $part['functionCall']['args']);
                continue;
            }

            if (!empty($part['functionResponse'])) {
                $parts[] = new FunctionResponsePart(['name' => $part['functionResponse']['name'], 'response' => $part['functionResponse']['response']]);
                continue;
            }

            if (!empty($part['inlineData'])) {
                $mimeType = MimeType::from($part['inlineData']['mimeType']);
                $parts[] = new FilePart($mimeType, $part['inlineData']['data']);
                continue;
            }
        }

        return new self(
            $parts,
            Role::from($content['role']),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'parts' => $this->parts,
            'role' => $this->role,
        ];
    }
}
