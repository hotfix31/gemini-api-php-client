<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Responses;

use GeminiAPI\Enums\FinishReason;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Candidate;
use GeminiAPI\Resources\CitationMetadata;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\FunctionCallPart;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Resources\PromptFeedback;
use GeminiAPI\Responses\GenerateContentResponse;
use GeminiAPI\Responses\UsageMetadata;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @coversClass \GeminiAPI\Responses\GenerateContentResponse
 */
class GenerateContentResponseTest extends TestCase
{
    private array $candidateData;
    private array $promptFeedbackData;
    private array $usageMetadataData;

    protected function setUp(): void
    {
        $this->candidateData = [
            'citationMetadata' => [
                'citationSources' => [
                    [
                        'startIndex' => 0,
                        'endIndex' => 10,
                        'uri' => 'https://example.com',
                        'license' => 'MIT'
                    ]
                ]
            ],
            'safetyRatings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'probability' => 'NEGLIGIBLE',
                    'blocked' => false
                ]
            ],
            'content' => [
                'parts' => [
                    ['text' => 'Test response']
                ],
                'role' => 'model'
            ],
            'finishReason' => 'STOP',
            'index' => 0
        ];

        $this->promptFeedbackData = [
            'blockReason' => null,
            'safetyRatings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'probability' => 'NEGLIGIBLE',
                    'blocked' => false
                ]
            ]
        ];

        $this->usageMetadataData = [
            'promptTokenCount' => 10,
            'candidatesTokenCount' => 20,
            'totalTokenCount' => 30,
            'promptTokensDetails' => [
                [
                    'modality' => 'text',
                    'tokenCount' => 10
                ]
            ],
            'thoughtsTokenCount' => 0
        ];
    }

    public function testConstructor(): void
    {
        $candidate = Candidate::fromArray($this->candidateData);
        $promptFeedback = PromptFeedback::fromArray($this->promptFeedbackData);

        $response = new GenerateContentResponse([$candidate], $promptFeedback);

        $this->assertSame([$candidate], $response->candidates);
        $this->assertSame($promptFeedback, $response->promptFeedback);
    }

    public function testPartsWithSingleCandidate(): void
    {
        $candidate = Candidate::fromArray($this->candidateData);
        $response = new GenerateContentResponse([$candidate]);

        $parts = $response->parts();
        $this->assertCount(1, $parts);
        $this->assertInstanceOf(TextPart::class, $parts[0]);
        $this->assertSame('Test response', $parts[0]->text);
    }

    public function testPartsWithNoCandidates(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('The `GenerateContentResponse::parts()` quick accessor only works for a single candidate, but none were returned.');

        $response = new GenerateContentResponse([]);
        $response->parts();
    }

    public function testPartsWithMultipleCandidates(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('The `GenerateContentResponse::parts()` quick accessor only works with a single candidate.');

        $candidate1 = Candidate::fromArray($this->candidateData);
        $candidate2 = Candidate::fromArray($this->candidateData);

        $response = new GenerateContentResponse([$candidate1, $candidate2]);
        $response->parts();
    }

    public function testTextWithSingleTextPart(): void
    {
        $candidate = Candidate::fromArray($this->candidateData);
        $response = new GenerateContentResponse([$candidate]);

        $this->assertSame('Test response', $response->text());
    }

    public function testTextWithMultipleParts(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('The `GenerateContentResponse::text()` quick accessor only works for simple (single-`Part`) text responses.');

        $candidateData = $this->candidateData;
        $candidateData['content']['parts'] = [
            ['text' => 'Test response 1'],
            ['text' => 'Test response 2']
        ];

        $candidate = Candidate::fromArray($candidateData);
        $response = new GenerateContentResponse([$candidate]);
        $response->text();
    }

    public function testTextWithNonTextPart(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('The `GenerateContentResponse::text()` quick accessor only works for simple (single-`Part`) text responses.');

        $candidateData = $this->candidateData;
        $candidateData['content']['parts'] = [
            [
                'functionCall' => [
                    'name' => 'testFunction',
                    'args' => ['param' => 'value']
                ]
            ]
        ];

        $candidate = Candidate::fromArray($candidateData);
        $response = new GenerateContentResponse([$candidate]);
        $response->text();
    }

    public function testFunctionCallWithSingleFunctionCallPart(): void
    {
        $candidateData = $this->candidateData;
        $candidateData['content']['parts'] = [
            [
                'functionCall' => [
                    'name' => 'testFunction',
                    'args' => ['param' => 'value']
                ]
            ]
        ];

        $candidate = Candidate::fromArray($candidateData);
        $response = new GenerateContentResponse([$candidate]);

        $functionCall = $response->functionCall();
        $this->assertSame('testFunction', $functionCall['name']);
        $this->assertSame(['param' => 'value'], $functionCall['args']);
    }

    public function testFunctionCallWithMultipleParts(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage(
            'The `GenerateContentResponse::functionCall()` quick accessor ' .
            'only works for simple (single-`Part`) text responses. ' .
            'This response contains multiple `Parts`. ' .
            'Use the `GenerateContentResponse::parts()` accessor ' .
            'or the full `GenerateContentResponse.candidates[index].content.parts` lookup instead'
        );

        $response = new GenerateContentResponse([
            new Candidate(
                new Content([
                    new TextPart('test'),
                    new FunctionCallPart('test', []),
                ], Role::Model),
                FinishReason::STOP,
                new CitationMetadata(),
                [],
                1,
                1,
            ),
        ]);

        $response->functionCall();
    }

    public function testFunctionCallWithNonFunctionCallPart(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage(
            'The `GenerateContentResponse::functionCall()` quick accessor ' .
            'only works for simple (single-`Part`) text responses. ' .
            'This response contains multiple `Parts`. ' .
            'Use the `GenerateContentResponse::parts()` accessor ' .
            'or the full `GenerateContentResponse.candidates[index].content.parts` lookup instead'
        );

        $response = new GenerateContentResponse([
            new Candidate(
                new Content([
                    new TextPart('test'),
                ], Role::Model),
                FinishReason::STOP,
                new CitationMetadata(),
                [],
                1,
                1,
            ),
        ]);

        $response->functionCall();
    }

    public function testFromArray(): void
    {
        $array = [
            'promptFeedback' => $this->promptFeedbackData,
            'candidates' => [$this->candidateData],
            'usageMetadata' => $this->usageMetadataData,
            'modelVersion' => '1.0'
        ];

        $response = GenerateContentResponse::fromArray($array);

        $this->assertCount(1, $response->candidates);
        $this->assertInstanceOf(PromptFeedback::class, $response->promptFeedback);
    }

    public function testFromArrayWithoutOptionalFields(): void
    {
        $array = [
            'candidates' => [$this->candidateData]
        ];

        $response = GenerateContentResponse::fromArray($array);

        $this->assertCount(1, $response->candidates);
        $this->assertNull($response->promptFeedback);
    }
} 