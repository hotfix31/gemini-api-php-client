<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Requests;

use GeminiAPI\Enums\Role;
use GeminiAPI\Enums\HarmCategory;
use GeminiAPI\Enums\HarmBlockThreshold;
use GeminiAPI\GenerationConfig;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Requests\GenerateContentStreamRequest;
use GeminiAPI\SafetySetting;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @coversClass \GeminiAPI\Requests\GenerateContentStreamRequest
 */
class GenerateContentStreamRequestTest extends TestCase
{
    private Content $content;
    private string $modelName = 'gemini-pro';

    protected function setUp(): void
    {
        $this->content = new Content([new TextPart('test')], Role::User);
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $this->assertSame($this->modelName, $request->modelName);
        $this->assertSame([$this->content], $request->contents);
        $this->assertEmpty($request->safetySettings);
        $this->assertNull($request->generationConfig);
        $this->assertNull($request->systemInstruction);
        $this->assertEmpty($request->tools);
    }

    public function testConstructorWithAllParameters(): void
    {
        $safetySettings = [new SafetySetting(HarmCategory::HARM_CATEGORY_HARASSMENT, HarmBlockThreshold::BLOCK_NONE)];
        $generationConfig = new GenerationConfig();
        $systemInstruction = new Content([new TextPart('system')], Role::User);
        $tools = [['type' => 'test']];

        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
            $safetySettings,
            $generationConfig,
            $systemInstruction,
            $tools
        );

        $this->assertSame($this->modelName, $request->modelName);
        $this->assertSame([$this->content], $request->contents);
        $this->assertSame($safetySettings, $request->safetySettings);
        $this->assertSame($generationConfig, $request->generationConfig);
        $this->assertSame($systemInstruction, $request->systemInstruction);
        $this->assertSame($tools, $request->tools);
    }

    public function testConstructorThrowsExceptionForInvalidContents(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new GenerateContentStreamRequest(
            $this->modelName,
            ['invalid'],
        );
    }

    public function testConstructorThrowsExceptionForInvalidSafetySettings(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
            ['invalid'],
        );
    }

    public function testGetOperation(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $this->assertSame('models/gemini-pro:streamGenerateContent', $request->getOperation());
    }

    public function testGetHttpMethod(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $this->assertSame('POST', $request->getHttpMethod());
    }

    public function testGetHttpPayload(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $expectedJson = json_encode([
            'model' => 'models/gemini-pro',
            'contents' => [$this->content],
        ]);

        $this->assertSame($expectedJson, $request->getHttpPayload());
    }

    public function testJsonSerializeWithMinimalParameters(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $expected = [
            'model' => 'models/gemini-pro',
            'contents' => [$this->content],
        ];

        $this->assertEquals($expected, $request->jsonSerialize());
    }

    public function testJsonSerializeWithAllParameters(): void
    {
        $safetySettings = [new SafetySetting(HarmCategory::HARM_CATEGORY_HARASSMENT, HarmBlockThreshold::BLOCK_NONE)];
        $generationConfig = new GenerationConfig();
        $systemInstruction = new Content([new TextPart('system')], Role::User);
        $tools = [['type' => 'test']];

        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
            $safetySettings,
            $generationConfig,
            $systemInstruction,
            $tools
        );

        $expected = [
            'model' => sprintf('models/%s', $this->modelName),
            'contents' => [$this->content],
            'safetySettings' => $safetySettings,
            'generationConfig' => $generationConfig,
            'systemInstruction' => $systemInstruction,
            'tools' => $tools,
        ];

        $this->assertEquals($expected, $request->jsonSerialize());
    }

    public function testToString(): void
    {
        $request = new GenerateContentStreamRequest(
            $this->modelName,
            [$this->content],
        );

        $expectedJson = json_encode([
            'model' => sprintf('models/%s', $this->modelName),
            'contents' => [$this->content],
        ]);

        $this->assertSame($expectedJson, (string)$request);
    }
} 