<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources;

use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Resources\GoogleSearch;
use GeminiAPI\Resources\Tool;
use PHPUnit\Framework\TestCase;

class ToolTest extends TestCase
{
    public function testConstructor(): void
    {
        $functionDeclarations = [
            new FunctionCall('test', 'Test description', ['param' => 'string'])
        ];
        
        $tool = new Tool($functionDeclarations);
        
        $this->assertSame($functionDeclarations, $tool->functionDeclarations);
    }

    public function testAddFunctionCall(): void
    {
        $tool = new Tool([]);
        $name = 'test';
        $description = 'Test description';
        $parameters = ['param' => 'string'];

        $result = $tool->addFunctionCall($name, $description, $parameters);

        $this->assertInstanceOf(Tool::class, $result);
        $this->assertCount(1, $tool->functionDeclarations);
        $this->assertInstanceOf(FunctionCall::class, $tool->functionDeclarations[0]);
    }

    public function testStaticFunctionCall(): void
    {
        $name = 'test';
        $description = 'Test description';
        $parameters = ['param' => 'string'];

        $tool = Tool::functionCall($name, $description, $parameters);

        $this->assertInstanceOf(Tool::class, $tool);
        $this->assertCount(1, $tool->functionDeclarations);
        $this->assertInstanceOf(FunctionCall::class, $tool->functionDeclarations[0]);
    }

    public function testFromArrayWithFunctionDeclarations(): void
    {
        $tools = [
            'function_declarations' => [
                [
                    'name' => 'test',
                    'description' => 'Test description',
                    'parameters' => ['param' => 'string']
                ]
            ],
            'role' => 'user'
        ];

        $tool = Tool::fromArray($tools);

        $this->assertInstanceOf(Tool::class, $tool);
        $this->assertCount(1, $tool->functionDeclarations);
        $this->assertInstanceOf(FunctionCall::class, $tool->functionDeclarations[0]);
    }

    public function testFromArrayWithGoogleSearch(): void
    {
        $tools = [
            'function_declarations' => [],
            'role' => 'user',
            'google_search_retrieval' => ['query' => 'test search']
        ];

        $tool = Tool::fromArray($tools);

        $this->assertInstanceOf(Tool::class, $tool);
        $this->assertCount(1, $tool->functionDeclarations);
        $this->assertInstanceOf(GoogleSearch::class, $tool->functionDeclarations[0]);
    }

    public function testFromArrayWithEmptyFunctionName(): void
    {
        $tools = [
            'function_declarations' => [
                [
                    'name' => '',
                    'description' => 'Test description',
                    'parameters' => ['param' => 'string']
                ]
            ],
            'role' => 'user'
        ];

        $tool = Tool::fromArray($tools);

        $this->assertInstanceOf(Tool::class, $tool);
        $this->assertCount(0, $tool->functionDeclarations);
    }
} 