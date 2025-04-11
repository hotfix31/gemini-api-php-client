<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit;

use GeminiAPI\Enums\HarmCategory;
use GeminiAPI\Enums\HarmBlockThreshold;
use GeminiAPI\SafetySetting;
use PHPUnit\Framework\TestCase;

/**
 * @coversClass \GeminiAPI\SafetySetting
 */
class SafetySettingTest extends TestCase
{
    public function testConstructor(): void
    {
        $safetySetting = new SafetySetting(
            HarmCategory::HARM_CATEGORY_HARASSMENT,
            HarmBlockThreshold::BLOCK_NONE
        );

        $this->assertSame(HarmCategory::HARM_CATEGORY_HARASSMENT, $safetySetting->category);
        $this->assertSame(HarmBlockThreshold::BLOCK_NONE, $safetySetting->threshold);
    }

    public function testJsonSerialize(): void
    {
        $safetySetting = new SafetySetting(
            HarmCategory::HARM_CATEGORY_HARASSMENT,
            HarmBlockThreshold::BLOCK_NONE
        );

        $expected = [
            'category' => HarmCategory::HARM_CATEGORY_HARASSMENT->value,
            'threshold' => HarmBlockThreshold::BLOCK_NONE->value,
        ];

        $this->assertSame($expected, $safetySetting->jsonSerialize());
    }

    public function testToString(): void
    {
        $safetySetting = new SafetySetting(
            HarmCategory::HARM_CATEGORY_HARASSMENT,
            HarmBlockThreshold::BLOCK_NONE
        );

        $expected = json_encode([
            'category' => HarmCategory::HARM_CATEGORY_HARASSMENT->value,
            'threshold' => HarmBlockThreshold::BLOCK_NONE->value,
        ]);

        $this->assertSame($expected, (string)$safetySetting);
    }
} 