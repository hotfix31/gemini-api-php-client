<?php

declare(strict_types=1);

namespace GeminiAPI\Enums;

enum ModelName: string
{
    case Default = 'models/text-bison-001';
    case GeminiPro = 'models/gemini-1.5-pro';
    case GeminiProExp = 'models/gemini-2.5-pro-exp-03-25';
    case GeminiFlash = 'models/gemini-2.0-flash';
    case GeminiFlashExp = 'models/gemini-2.0-flash-exp';
    case GeminiProVision = 'models/gemini-pro-vision';
    case Embedding = 'models/embedding-001';
    case AQA = 'models/aqa';
}
