<?php

declare(strict_types=1);

namespace GeminiAPI\Enums;

enum ModelName: string
{
    case Default = 'models/text-bison-001';
    case GeminiPro = 'models/gemini-1.5-pro';
    case GeminiProExp = 'models/gemini-1.5-pro';
    case GeminiFlash = 'models/gemini-1.5-flash';
    case GeminiFlashExp = 'models/gemini-exp-1206';
    case GeminiProVision = 'models/gemini-pro-vision';
    case Embedding = 'models/embedding-001';
    case AQA = 'models/aqa';
}
