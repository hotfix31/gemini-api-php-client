<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources;

use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Resources\FunctionParameter;
use PHPUnit\Framework\TestCase;

class FunctionCallTest extends TestCase
{
    private string $name = 'testFunction';
    private string $description = 'Test function description';
    private array $parameters = [];

    public function setUp(): void
    {
        parent::setUp();
        
        $this->parameters = [
            'param1' => new FunctionParameter('string', 'param1'),
            'param2' => new FunctionParameter('integer', 'param2')
        ];
    }

    public function testConstructor(): void
    {
        $functionCall = new FunctionCall($this->name, $this->description, $this->parameters);

        $this->assertSame($this->name, $functionCall->name);
        $this->assertSame($this->description, $functionCall->description);
        $this->assertSame($this->parameters, $functionCall->parameters);
    }

    public function testConstructorWithDefaultParameters(): void
    {
        $functionCall = new FunctionCall($this->name, $this->description);

        $this->assertSame($this->name, $functionCall->name);
        $this->assertSame($this->description, $functionCall->description);
        $this->assertSame([], $functionCall->parameters);
    }

    public function testFromArray(): void
    {
        $array = [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => $this->parameters,
            'required' => [],
        ];

        $functionCall = FunctionCall::fromArray($array);

        $this->assertInstanceOf(FunctionCall::class, $functionCall);
        $this->assertSame($this->name, $functionCall->name);
        $this->assertSame($this->description, $functionCall->description);
        $this->assertSame($this->parameters, $functionCall->parameters);
    }

    public function testJsonSerialize(): void
    {
        $functionCall = new FunctionCall($this->name, $this->description, $this->parameters);

        $expected = [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => $this->parameters,
            'required' => [],
        ];

        $this->assertSame($expected, $functionCall->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($functionCall));
    }
} 