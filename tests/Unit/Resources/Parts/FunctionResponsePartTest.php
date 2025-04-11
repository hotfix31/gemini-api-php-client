<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources\Parts;

use GeminiAPI\Resources\Parts\FunctionResponsePart;
use PHPUnit\Framework\TestCase;

class FunctionResponsePartTest extends TestCase
{
    private array $functionResponse;

    protected function setUp(): void
    {
        $this->functionResponse = [
            'name' => 'testFunction',
            'response' => 'Function response content'
        ];
    }

    public function testConstructor(): void
    {
        $functionResponsePart = new FunctionResponsePart($this->functionResponse);

        $this->assertSame($this->functionResponse, $functionResponsePart->functionResponse);
    }

    public function testJsonSerialize(): void
    {
        $functionResponsePart = new FunctionResponsePart($this->functionResponse);

        $expected = [
            'functionResponse' => [
                'name' => $this->functionResponse['name'],
                'response' => [
                    'name' => $this->functionResponse['name'],
                    'content' => $this->functionResponse['response'],
                ],
            ]
        ];

        $this->assertSame($expected, $functionResponsePart->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($functionResponsePart));
    }

    public function testToString(): void
    {
        $functionResponsePart = new FunctionResponsePart($this->functionResponse);
        $expected = json_encode([
            'functionResponse' => [
                'name' => $this->functionResponse['name'],
                'response' => [
                    'name' => $this->functionResponse['name'],
                    'content' => $this->functionResponse['response'],
                ],
            ]
        ]);

        $this->assertSame($expected, (string)$functionResponsePart);
    }

    public function testImplementsInterfaces(): void
    {
        $functionResponsePart = new FunctionResponsePart($this->functionResponse);

        $this->assertInstanceOf(\JsonSerializable::class, $functionResponsePart);
        $this->assertInstanceOf(\GeminiAPI\Resources\Parts\PartInterface::class, $functionResponsePart);
    }
} 