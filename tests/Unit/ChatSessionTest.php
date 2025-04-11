<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\ChatSession;
use GeminiAPI\Enums\FinishReason;
use GeminiAPI\Enums\Role;
use GeminiAPI\GenerationConfig;
use GeminiAPI\GenerativeModel;
use GeminiAPI\Resources\Candidate;
use GeminiAPI\Resources\CitationMetadata;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use stdClass;

/**
 * @coversClass \GeminiAPI\ChatSession
 */
class ChatSessionTest extends TestCase
{
    /** @var MockObject&GenerativeModel */
    private MockObject $model;
    private ChatSession $chatSession;

    protected function setUp(): void
    {
        $this->model = $this->createMock(GenerativeModel::class);
        $this->chatSession = new ChatSession($this->model);
    }

    public function testSendMessage(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $expectedResponse = $this->createMock(GenerateContentResponse::class);
        $expectedContent = new Content([$part], Role::User);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentWithContents')
            ->with([$expectedContent])
            ->willReturn($expectedResponse);

        $response = $this->chatSession->sendMessage($part);

        $this->assertSame($expectedResponse, $response);
    }

    public function testSendMessageWithModelResponse(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $modelResponse = new TextPart('Model response');
        $expectedContent = new Content([$part], Role::User);

        $modelContent = new Content([$modelResponse], Role::Model);
        $candidate = new Candidate(
            $modelContent,
            FinishReason::STOP,
            new CitationMetadata(),
            [],
            1,
            0
        );

        $expectedResponse = new GenerateContentResponse([$candidate]);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentWithContents')
            ->with([$expectedContent])
            ->willReturn($expectedResponse);

        $response = $this->chatSession->sendMessage($part);

        $history = $this->chatSession->history();
        $this->assertCount(2, $history);
        $this->assertEquals($expectedContent, $history[0]);
        $this->assertEquals($modelContent, $history[1]);
    }

    public function testSendMessageWithEmptyCandidates(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $expectedContent = new Content([$part], Role::User);

        $expectedResponse = new GenerateContentResponse([]);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentWithContents')
            ->with([$expectedContent])
            ->willReturn($expectedResponse);

        $response = $this->chatSession->sendMessage($part);

        $history = $this->chatSession->history();
        $this->assertCount(1, $history);
        $this->assertEquals($expectedContent, $history[0]);
    }

    public function testSendMessageStream(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $callback = function (GenerateContentResponse $response) {};
        $expectedContent = new Content([$part], Role::User);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentStreamWithContents')
            ->with($this->isType('callable'), [$expectedContent]);

        $this->chatSession->sendMessageStream($callback, $part);
    }

    public function testSendMessageStreamWithResponse(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $modelResponse = new TextPart('Model response');
        $expectedContent = new Content([$part], Role::User);

        $modelContent = new Content([$modelResponse], Role::Model);
        $candidate = new Candidate(
            $modelContent,
            FinishReason::STOP,
            new CitationMetadata(),
            [],
            1,
            0
        );

        $mockResponse = new GenerateContentResponse([$candidate]);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentStreamWithContents')
            ->willReturnCallback(function ($callback, $contents) use ($mockResponse) {
                $callback($mockResponse);
            });

        $this->chatSession->sendMessageStream(function ($response) use ($mockResponse) {
            $this->assertSame($mockResponse, $response);
        }, $part);

        $history = $this->chatSession->history();
        $this->assertCount(2, $history);
        $this->assertEquals($expectedContent, $history[0]);
        $this->assertEquals($modelContent, $history[1]);
    }

    public function testHistory(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $expectedResponse = $this->createMock(GenerateContentResponse::class);
        $expectedContent = new Content([$part], Role::User);

        $this->model->expects($this->once())
            ->method('withGenerationConfig')
            ->willReturnSelf();

        $this->model->expects($this->once())
            ->method('generateContentWithContents')
            ->with([$expectedContent])
            ->willReturn($expectedResponse);

        $this->chatSession->sendMessage($part);

        $history = $this->chatSession->history();
        $this->assertCount(1, $history);
        $this->assertEquals($expectedContent, $history[0]);
    }

    public function testWithHistory(): void
    {
        $text = 'Test message';
        $part = new TextPart($text);
        $content = new Content([$part], Role::User);
        $history = [$content];

        $newChatSession = $this->chatSession->withHistory($history);

        $this->assertNotSame($this->chatSession, $newChatSession);
        $this->assertEquals($history, $newChatSession->history());
    }

    public function testWithHistoryWithInvalidContent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $invalidHistory = [new stdClass()];
        $this->chatSession->withHistory($invalidHistory);
    }
} 