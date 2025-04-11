<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources\Parts;

use GeminiAPI\Resources\Parts\TextPart;
use PHPUnit\Framework\TestCase;

class TextPartTest extends TestCase
{
    private string $text = 'Sample text content';

    public function testConstructor(): void
    {
        $textPart = new TextPart($this->text);

        $this->assertSame($this->text, $textPart->text);
    }

    public function testJsonSerialize(): void
    {
        $textPart = new TextPart($this->text);

        $expected = ['text' => $this->text];

        $this->assertSame($expected, $textPart->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($textPart));
    }

    public function testToString(): void
    {
        $textPart = new TextPart($this->text);
        $expected = json_encode(['text' => $this->text]);

        $this->assertSame($expected, (string)$textPart);
    }

    public function testImplementsInterfaces(): void
    {
        $textPart = new TextPart($this->text);

        $this->assertInstanceOf(\JsonSerializable::class, $textPart);
        $this->assertInstanceOf(\GeminiAPI\Resources\Parts\PartInterface::class, $textPart);
    }
}
