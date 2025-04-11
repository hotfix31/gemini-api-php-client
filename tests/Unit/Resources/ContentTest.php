<?php

declare(strict_types=1);

namespace GeminiAPI\Tests\Unit\Resources;

use GeminiAPI\Enums\MimeType;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\FilePart;
use GeminiAPI\Resources\Parts\ImagePart;
use GeminiAPI\Resources\Parts\TextPart;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @coversClass \GeminiAPI\Resources\Content
 */
class ContentTest extends TestCase
{
    public function testConstructor(): void
    {
        $parts = [new TextPart('test')];
        $content = new Content($parts, Role::User);

        $this->assertSame($parts, $content->parts);
        $this->assertSame(Role::User, $content->role);
    }

    public function testConstructorThrowsExceptionForInvalidParts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Content(['invalid'], Role::User);
    }

    public function testAddText(): void
    {
        $content = new Content([], Role::User);
        $content = $content->addText('test');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(TextPart::class, $content->parts[0]);
        $this->assertSame('test', $content->parts[0]->text);
    }

    public function testAddImage(): void
    {
        $content = new Content([], Role::User);
        $content = $content->addImage(MimeType::IMAGE_JPEG, 'base64image');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(ImagePart::class, $content->parts[0]);
        $this->assertSame(MimeType::IMAGE_JPEG, $content->parts[0]->mimeType);
        $this->assertSame('base64image', $content->parts[0]->data);
    }

    public function testAddFile(): void
    {
        $content = new Content([], Role::User);
        $content = $content->addFile(MimeType::FILE_PDF, 'base64file');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(FilePart::class, $content->parts[0]);
        $this->assertSame(MimeType::FILE_PDF, $content->parts[0]->mimeType);
        $this->assertSame('base64file', $content->parts[0]->data);
    }

    public function testText(): void
    {
        $content = Content::text('test');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(TextPart::class, $content->parts[0]);
        $this->assertSame('test', $content->parts[0]->text);
        $this->assertSame(Role::User, $content->role);
    }

    public function testTextWithCustomRole(): void
    {
        $content = Content::text('test', Role::Model);

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(TextPart::class, $content->parts[0]);
        $this->assertSame('test', $content->parts[0]->text);
        $this->assertSame(Role::Model, $content->role);
    }

    public function testImage(): void
    {
        $content = Content::image(MimeType::IMAGE_JPEG, 'base64image');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(ImagePart::class, $content->parts[0]);
        $this->assertSame(MimeType::IMAGE_JPEG, $content->parts[0]->mimeType);
        $this->assertSame('base64image', $content->parts[0]->data);
        $this->assertSame(Role::User, $content->role);
    }

    public function testImageWithCustomRole(): void
    {
        $content = Content::image(MimeType::IMAGE_JPEG, 'base64image', Role::Model);

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(ImagePart::class, $content->parts[0]);
        $this->assertSame(MimeType::IMAGE_JPEG, $content->parts[0]->mimeType);
        $this->assertSame('base64image', $content->parts[0]->data);
        $this->assertSame(Role::Model, $content->role);
    }

    public function testFile(): void
    {
        $content = Content::file(MimeType::FILE_PDF, 'base64file');

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(FilePart::class, $content->parts[0]);
        $this->assertSame(MimeType::FILE_PDF, $content->parts[0]->mimeType);
        $this->assertSame('base64file', $content->parts[0]->data);
        $this->assertSame(Role::User, $content->role);
    }

    public function testFileWithCustomRole(): void
    {
        $content = Content::file(MimeType::FILE_PDF, 'base64file', Role::Model);

        $this->assertCount(1, $content->parts);
        $this->assertInstanceOf(FilePart::class, $content->parts[0]);
        $this->assertSame(MimeType::FILE_PDF, $content->parts[0]->mimeType);
        $this->assertSame('base64file', $content->parts[0]->data);
        $this->assertSame(Role::Model, $content->role);
    }
}
