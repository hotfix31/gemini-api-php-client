<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\GenerationConfig;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @coversClass \GeminiAPI\GenerationConfig
 */
class GenerationConfigTest extends TestCase
{
    private GenerationConfig $config;

    protected function setUp(): void
    {
        $this->config = new GenerationConfig();
    }

    public function testWithCandidateCount(): void
    {
        $result = $this->config->withCandidateCount(2);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['candidateCount' => 2], $result->jsonSerialize());
    }

    public function testWithCandidateCountThrowsOnNegative(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Candidate count is negative');

        $this->config->withCandidateCount(-1);
    }

    public function testWithStopSequences(): void
    {
        $stopSequences = ['stop1', 'stop2'];
        $result = $this->config->withStopSequences($stopSequences);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['stopSequences' => $stopSequences], $result->jsonSerialize());
    }

    public function testWithMaxOutputTokens(): void
    {
        $result = $this->config->withMaxOutputTokens(100);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['maxOutputTokens' => 100], $result->jsonSerialize());
    }

    public function testWithMaxOutputTokensThrowsOnNegative(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Max output tokens is negative');

        $this->config->withMaxOutputTokens(-1);
    }

    public function testWithTemperature(): void
    {
        $result = $this->config->withTemperature(0.7);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['temperature' => 0.7], $result->jsonSerialize());
    }

    public function testWithTemperatureThrowsOnInvalidValue(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Temperature is negative or more than 1');

        $this->config->withTemperature(1.1);
    }

    public function testWithTopP(): void
    {
        $result = $this->config->withTopP(0.8);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['topP' => 0.8], $result->jsonSerialize());
    }

    public function testWithTopPThrowsOnNegative(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Top-p is negative');

        $this->config->withTopP(-0.1);
    }

    public function testWithTopK(): void
    {
        $result = $this->config->withTopK(40);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['topK' => 40], $result->jsonSerialize());
    }

    public function testWithTopKThrowsOnNegative(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Top-k is negative');

        $this->config->withTopK(-1);
    }

    public function testWithResponseMimeType(): void
    {
        $result = $this->config->withResponseMimeType('application/json');

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['responseMimeType' => 'application/json'], $result->jsonSerialize());
    }

    public function testWithResponseSchema(): void
    {
        $schema = [
            [
                'type' => 'object',
                'schema' => ['properties' => ['name' => ['type' => 'string']]],
            ],
        ];

        $result = $this->config->withResponseSchema($schema);

        $this->assertNotSame($this->config, $result);
        $this->assertEquals(['responseSchema' => $schema], $result->jsonSerialize());
    }

    public function testChainedConfiguration(): void
    {
        $result = $this->config
            ->withCandidateCount(2)
            ->withTemperature(0.7)
            ->withTopP(0.8)
            ->withMaxOutputTokens(100);

        $expected = [
            'candidateCount' => 2,
            'temperature' => 0.7,
            'topP' => 0.8,
            'maxOutputTokens' => 100,
        ];

        $this->assertEquals($expected, $result->jsonSerialize());
    }
}
