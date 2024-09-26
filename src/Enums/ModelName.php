<?php

declare(strict_types=1);

namespace GeminiAPI\Enums;

enum ModelName: string
{
    case Default = 'models/text-bison-001';
    case GeminiPro = 'models/gemini-pro';
    case GeminiPro10 = 'models/gemini-1.0-pro';
    case GeminiPro10Latest = 'models/gemini-1.0-pro-latest';
    case GeminiPro15 = 'models/gemini-1.5-pro';
    case GeminiPro15002 = 'models/gemini-1.5-pro-002';
    case GeminiFlash15 = 'models/gemini-1.5-flash';
    case GeminiFlash15002 = 'models/gemini-1.5-flash-002';
    case GeminiProVision = 'models/gemini-pro-vision';
    case Embedding = 'models/embedding-001';
    case AQA = 'models/aqa';
}
