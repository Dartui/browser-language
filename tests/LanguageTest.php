<?php
declare(strict_types=1);

namespace Tests;

use Dartui\BrowserLanguage\Language;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    public function test_if_constructor_works(): void
    {
        $language = new Language('en-US', 0.5, 2);

        $this->assertSame('en-US', $language->getName());
        $this->assertSame(0.5, $language->getFactor());
        $this->assertSame(2, $language->getOrder());
    }

    public function test_if_parses_correct_string(): void
    {
        $language = Language::fromString('en;q=0.3', 5);

        $this->assertSame('en', $language->getName());
        $this->assertSame(0.3, $language->getFactor());
        $this->assertSame(5, $language->getOrder());
    }

    public function test_if_parses_wildcard(): void
    {
        $language = Language::fromString('*;q=0.2');

        $this->assertSame('*', $language->getName());
        $this->assertSame(0.2, $language->getFactor());
    }

    public function test_if_throws_exception_for_invalid_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot match name in given string.');

        Language::fromString('&invalid');
    }

    public function test_if_creates_correct_compareable_data(): void
    {
        $language = new Language('en', 0.3, 5);

        $this->assertSame('0.700_5', $language->toCompareableData());
    }
}
