<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources;

use GeminiAPI\Resources\GoogleSearch;
use PHPUnit\Framework\TestCase;

class GoogleSearchTest extends TestCase
{
    private array $tool = [
        'query' => 'test search',
        'options' => [
            'limit' => 5,
            'language' => 'en'
        ]
    ];

    public function testConstructor(): void
    {
        $googleSearch = new GoogleSearch($this->tool);

        $this->assertSame($this->tool, $googleSearch->tool);
    }

    public function testFromArray(): void
    {
        $array = [
            'tool' => $this->tool
        ];

        $googleSearch = GoogleSearch::fromArray($array);

        $this->assertInstanceOf(GoogleSearch::class, $googleSearch);
        $this->assertSame($this->tool, $googleSearch->tool);
    }

    public function testJsonSerialize(): void
    {
        $googleSearch = new GoogleSearch($this->tool);

        $expected = [
            'tool' => $this->tool
        ];

        $this->assertSame($expected, $googleSearch->jsonSerialize());
        $this->assertSame(json_encode($expected), json_encode($googleSearch));
    }
} 