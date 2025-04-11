<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\Client;
use GeminiAPI\GenerationConfig;
use GeminiAPI\GenerativeModel;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\FunctionCall;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\Tool;
use GeminiAPI\Responses\CountTokensResponse;
use GeminiAPI\Responses\GenerateContentResponse;
use GeminiAPI\SafetySetting;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @coversClass \GeminiAPI\GenerativeModel
 */
class GenerativeModelTest extends TestCase
{
    private Client $client;
    private GenerativeModel $model;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->model = new GenerativeModel($this->client, ModelName::GeminiPro->value);
    }

    public function testGenerateContent(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);
        $expectedResponse = $this->createMock(GenerateContentResponse::class);

        $this->client->expects($this->once())
            ->method('generateContent')
            ->willReturn($expectedResponse);

        $response = $this->model->generateContent($part);

        $this->assertSame($expectedResponse, $response);
    }

    public function testGenerateContentWithContents(): void
    {
        $content = new Content([new TextPart('Test content')], Role::User);
        $expectedResponse = $this->createMock(GenerateContentResponse::class);

        $this->client->expects($this->once())
            ->method('generateContent')
            ->willReturn($expectedResponse);

        $response = $this->model->generateContentWithContents([$content]);

        $this->assertSame($expectedResponse, $response);
    }

    public function testStartChat(): void
    {
        $chatSession = $this->model->startChat();

        $this->assertNotNull($chatSession);
        $this->assertInstanceOf(\GeminiAPI\ChatSession::class, $chatSession);
    }

    public function testCountTokens(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);
        $expectedResponse = $this->createMock(CountTokensResponse::class);

        $this->client->expects($this->once())
            ->method('countTokens')
            ->willReturn($expectedResponse);

        $response = $this->model->countTokens($part);

        $this->assertSame($expectedResponse, $response);
    }

    public function testWithAddedSafetySetting(): void
    {
        $safetySetting = $this->createMock(SafetySetting::class);
        $newModel = $this->model->withAddedSafetySetting($safetySetting);

        $this->assertNotSame($this->model, $newModel);
        $this->assertInstanceOf(GenerativeModel::class, $newModel);
    }

    public function testWithGenerationConfig(): void
    {
        $config = new GenerationConfig();
        $newModel = $this->model->withGenerationConfig($config);

        $this->assertNotSame($this->model, $newModel);
        $this->assertInstanceOf(GenerativeModel::class, $newModel);
    }

    public function testWithSystemInstruction(): void
    {
        $instruction = 'System instruction';
        $newModel = $this->model->withSystemInstruction($instruction);

        $this->assertNotSame($this->model, $newModel);
        $this->assertInstanceOf(GenerativeModel::class, $newModel);
    }

    public function testWithTool(): void
    {
        $functionCall = new FunctionCall('test', 'Test function', ['type' => 'object']);
        $tool = new Tool([$functionCall]);
        $newModel = $this->model->withTool($tool);

        $this->assertNotSame($this->model, $newModel);
        $this->assertInstanceOf(GenerativeModel::class, $newModel);
    }

    public function testGenerateContentStream(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);
        $callback = function (GenerateContentResponse $response) {};

        $this->client->expects($this->once())
            ->method('generateContentStream');

        $this->model->generateContentStream($callback, [$part]);
    }

    public function testClientExceptionHandling(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);

        $this->client->expects($this->once())
            ->method('generateContent')
            ->willThrowException($this->createMock(ClientExceptionInterface::class));

        $this->expectException(ClientExceptionInterface::class);
        $this->model->generateContent($part);
    }
}
