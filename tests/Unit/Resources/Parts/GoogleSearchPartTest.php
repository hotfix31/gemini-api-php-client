<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources\Parts;

use GeminiAPI\Resources\Parts\GoogleSearchPart;
use PHPUnit\Framework\TestCase;

class GoogleSearchPartTest extends TestCase
{
    public function testConstructor(): void
    {
        $googleSearch = [
            'name' => 'searchQuery',
            'args' => ['query' => 'test search']
        ];
        
        $part = new GoogleSearchPart($googleSearch);
        
        $this->assertSame($googleSearch, $part->googleSearch);
    }

    public function testJsonSerialize(): void
    {
        $googleSearch = [
            'name' => 'searchQuery',
            'args' => ['query' => 'test search']
        ];
        
        $part = new GoogleSearchPart($googleSearch);
        $serialized = $part->jsonSerialize();
        
        $this->assertIsArray($serialized);
        $this->assertArrayHasKey('name', $serialized);
        $this->assertArrayHasKey('args', $serialized);
        $this->assertEquals('searchQuery', $serialized['name']);
        $this->assertEquals(['query' => 'test search'], $serialized['args']);
    }

    public function testToString(): void
    {
        $googleSearch = [
            'name' => 'searchQuery',
            'args' => ['query' => 'test search']
        ];
        
        $part = new GoogleSearchPart($googleSearch);
        $string = (string)$part;
        
        $this->assertJson($string);
        $decoded = json_decode($string, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('name', $decoded);
        $this->assertArrayHasKey('args', $decoded);
    }

    public function testJsonSerializeStructure(): void
    {
        $googleSearch = [
            'name' => 'searchQuery',
            'args' => [
                'query' => 'test search',
                'options' => ['limit' => 10]
            ]
        ];
        
        $part = new GoogleSearchPart($googleSearch);
        $serialized = $part->jsonSerialize();
        
        $this->assertIsArray($serialized);
        $this->assertEquals($googleSearch['name'], $serialized['name']);
        $this->assertEquals($googleSearch['args'], $serialized['args']);
    }
} 