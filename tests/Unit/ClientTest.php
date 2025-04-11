<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\Client;
use GeminiAPI\ClientInterface;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\Enums\Role;
use GeminiAPI\Requests\GenerateContentRequest;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use ReflectionClass;

/**
 * @coversClass \GeminiAPI\Client
 */
class ClientTest extends TestCase
{
    private Client $client;
    private HttpClientInterface&MockObject $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $this->client = new Client(
            'test-api-key',
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
        );
    }

    public function testConstructor(): void
    {
        $client = new Client('test-api-key');
        self::assertInstanceOf(Client::class, $client);
    }

    public function testWithBaseUrl(): void
    {
        $newClient = $this->client->withBaseUrl('https://api.example.com');
        self::assertNotSame($this->client, $newClient);
        
        $reflection = new ReflectionClass($newClient);
        $baseUrlProperty = $reflection->getProperty('baseUrl');
        $baseUrlProperty->setAccessible(true);
        self::assertSame('https://api.example.com', $baseUrlProperty->getValue($newClient));
    }

    public function testGeminiPro(): void
    {
        $model = $this->client->geminiPro();
        self::assertSame(ModelName::GeminiPro->value, $model->modelName);
    }

    public function testGeminiProVision(): void
    {
        $model = $this->client->geminiProVision();
        self::assertSame(ModelName::GeminiProVision->value, $model->modelName);
    }

    public function testGeminiPro10(): void
    {
        $model = $this->client->geminiPro10();
        self::assertSame(ModelName::GeminiPro10->value, $model->modelName);
    }

    public function testGeminiPro10Latest(): void
    {
        $model = $this->client->geminiPro10Latest();
        self::assertSame(ModelName::GeminiPro10Latest->value, $model->modelName);
    }

    public function testGeminiPro15(): void
    {
        $model = $this->client->geminiPro15();
        self::assertSame(ModelName::GeminiPro15->value, $model->modelName);
    }

    public function testGeminiProFlash15(): void
    {
        $model = $this->client->geminiProFlash1_5();
        self::assertSame(ModelName::GeminiPro15Flash->value, $model->modelName);
    }

    public function testGenerativeModel(): void
    {
        $model = $this->client->generativeModel('custom-model');
        self::assertSame('custom-model', $model->modelName);
    }

    public function testEmbeddingModel(): void
    {
        $model = $this->client->embeddingModel('custom-model');
        self::assertSame('custom-model', $model->modelName);
    }

    public function testWithV1BetaVersion(): void
    {
        $newClient = $this->client->withV1BetaVersion();
        self::assertNotSame($this->client, $newClient);
        
        $reflection = new ReflectionClass($newClient);
        $versionProperty = $reflection->getProperty('version');
        $versionProperty->setAccessible(true);
        self::assertSame(ClientInterface::API_VERSION_V1_BETA, $versionProperty->getValue($newClient));
    }

    public function testWithVersion(): void
    {
        $newClient = $this->client->withVersion('v2');
        self::assertNotSame($this->client, $newClient);
        
        $reflection = new ReflectionClass($newClient);
        $versionProperty = $reflection->getProperty('version');
        $versionProperty->setAccessible(true);
        self::assertSame('v2', $versionProperty->getValue($newClient));
    }

    public function testGenerateContentWithHttpRequest(): void
    {
        $httpRequest = $this->requestFactory->createRequest(
            'POST',
            'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent',
        );
        $httpRequest = $httpRequest
            ->withHeader('content-type', 'application/json')
            ->withHeader(ClientInterface::API_KEY_HEADER_NAME, 'test-api-key');
        
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse->expects($this->once())->method('getBody')->willReturn(
            $this->streamFactory->createStream(<<<BODY
            {
              "candidates": [
                {
                  "content": {
                    "parts": [
                      {
                        "text": "Hello, how can I help you today?"
                      }
                    ],
                    "role": "model"
                  },
                  "finishReason": "STOP",
                  "safetyRatings": [
                    {
                      "category": "HARM_CATEGORY_HARASSMENT",
                      "probability": "NEGLIGIBLE"
                    }
                  ]
                }
              ],
              "promptFeedback": {
                "safetyRatings": [
                  {
                    "category": "HARM_CATEGORY_HARASSMENT",
                    "probability": "NEGLIGIBLE"
                  }
                ]
              }
            }
            BODY)
        );

        $httpResponse->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) use ($httpRequest) {
                return $request->getMethod() === $httpRequest->getMethod() &&
                    (string)$request->getUri() === (string)$httpRequest->getUri() &&
                    $request->getHeaders() === $httpRequest->getHeaders();
            }))
            ->willReturn($httpResponse);

        $response = $this->client->generateContent(new GenerateContentRequest(
            'gemini-pro',
            [new Content([new TextPart('test')], Role::User)]
        ));

        $this->assertInstanceOf(GenerateContentResponse::class, $response);
        $this->assertCount(1, $response->candidates);
        $this->assertSame('Hello, how can I help you today?', $response->candidates[0]->content->parts[0]->text);
    }
}
