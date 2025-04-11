<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources\Parts;

use GeminiAPI\Resources\Parts\FunctionCallPart;
use PHPUnit\Framework\TestCase;

/**
 * @coversClass \GeminiAPI\Resources\Parts\FunctionCallPart
 */
class FunctionCallPartTest extends TestCase
{
    private string $name;
    private array $args;

    protected function setUp(): void
    {
        $this->name = 'testFunction';
        $this->args = [
            'param1' => 'value1',
            'param2' => 'value2'
        ];
    }

    public function testConstructor(): void
    {
        $functionCallPart = new FunctionCallPart($this->name, $this->args);

        $this->assertSame($this->name, $functionCallPart->name);
        $this->assertSame($this->args, $functionCallPart->args);
    }

    public function testJsonSerialize(): void
    {
        $functionCallPart = new FunctionCallPart($this->name, $this->args);

        $expected = [
            'functionCall' => [
                'name' => $this->name,
                'args' => $this->args
            ]
        ];

        $this->assertSame($expected, $functionCallPart->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($functionCallPart));
    }

    public function testToString(): void
    {
        $functionCallPart = new FunctionCallPart($this->name, $this->args);
        $expected = json_encode([
            'functionCall' => [
                'name' => $this->name,
                'args' => $this->args
            ]
        ]);

        $this->assertSame($expected, (string)$functionCallPart);
    }

    public function testImplementsInterfaces(): void
    {
        $functionCallPart = new FunctionCallPart($this->name, $this->args);

        $this->assertInstanceOf(\JsonSerializable::class, $functionCallPart);
        $this->assertInstanceOf(\GeminiAPI\Resources\Parts\PartInterface::class, $functionCallPart);
    }
} 