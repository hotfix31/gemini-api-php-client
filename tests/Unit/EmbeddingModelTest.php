<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\Client;
use GeminiAPI\EmbeddingModel;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\Enums\Role;
use GeminiAPI\Enums\TaskType;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\EmbedContentResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @coversClass \GeminiAPI\EmbeddingModel
 */
class EmbeddingModelTest extends TestCase
{
    private Client $client;
    private EmbeddingModel $model;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->model = new EmbeddingModel($this->client, ModelName::Embedding->value);
    }

    public function testEmbedContent(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);
        $expectedContent = new Content([$part], Role::User);
        $expectedResponse = $this->createMock(EmbedContentResponse::class);

        $this->client->expects($this->once())
            ->method('embedContent')
            ->willReturn($expectedResponse);

        $response = $this->model->embedContent($part);

        $this->assertSame($expectedResponse, $response);
    }

    public function testEmbedContentWithTitle(): void
    {
        $text = 'Test content';
        $title = 'Test Title';
        $part = new TextPart($text);
        $expectedContent = new Content([$part], Role::User);
        $expectedResponse = $this->createMock(EmbedContentResponse::class);

        $this->client->expects($this->once())
            ->method('embedContent')
            ->willReturn($expectedResponse);

        $response = $this->model->embedContentWithTitle($title, $part);

        $this->assertSame($expectedResponse, $response);
    }

    public function testWithTaskType(): void
    {
        $taskType = TaskType::RETRIEVAL_QUERY;
        $newModel = $this->model->withTaskType($taskType);

        $this->assertNotSame($this->model, $newModel);
        $this->assertInstanceOf(EmbeddingModel::class, $newModel);
    }

    public function testClientExceptionHandling(): void
    {
        $text = 'Test content';
        $part = new TextPart($text);

        $this->client->expects($this->once())
            ->method('embedContent')
            ->willThrowException($this->createMock(ClientExceptionInterface::class));

        $this->expectException(ClientExceptionInterface::class);
        $this->model->embedContent($part);
    }
}
