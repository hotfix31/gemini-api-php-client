<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources\Parts;

use GeminiAPI\Enums\MimeType;
use GeminiAPI\Resources\Parts\FilePart;
use PHPUnit\Framework\TestCase;

class FilePartTest extends TestCase
{
    private MimeType $mimeType;
    private string $data;

    protected function setUp(): void
    {
        $this->mimeType = MimeType::IMAGE_JPEG;
        $this->data = 'base64_encoded_data';
    }

    public function testConstructor(): void
    {
        $filePart = new FilePart($this->mimeType, $this->data);

        $this->assertSame($this->mimeType, $filePart->mimeType);
        $this->assertSame($this->data, $filePart->data);
    }

    public function testJsonSerialize(): void
    {
        $filePart = new FilePart($this->mimeType, $this->data);

        $expected = [
            'inlineData' => [
                'mimeType' => $this->mimeType->value,
                'data' => $this->data,
            ],
        ];

        $this->assertSame($expected, $filePart->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($filePart));
    }

    public function testToString(): void
    {
        $filePart = new FilePart($this->mimeType, $this->data);
        $expected = json_encode([
            'inlineData' => [
                'mimeType' => $this->mimeType->value,
                'data' => $this->data,
            ],
        ]);

        $this->assertSame($expected, (string)$filePart);
    }

    public function testImplementsInterfaces(): void
    {
        $filePart = new FilePart($this->mimeType, $this->data);

        $this->assertInstanceOf(\JsonSerializable::class, $filePart);
        $this->assertInstanceOf(\GeminiAPI\Resources\Parts\PartInterface::class, $filePart);
    }
} 