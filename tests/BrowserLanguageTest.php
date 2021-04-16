<?php
declare(strict_types=1);

namespace Tests;

use Dartui\BrowserLanguage\BrowserLanguage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BrowserLanguageTest extends TestCase
{
    public function test_if_sets_header_value_from_server_values(): void
    {
        /** Override browser Accept-Language header. */
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'test';

        $browserLanguage = new BrowserLanguage();

        $this->assertSame('test', $browserLanguage->getHeader());

        unset($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }

    public function test_if_allows_overriding_header_by_constructor(): void
    {
        $browserLanguage = new BrowserLanguage('test1');

        $this->assertSame('test1', $browserLanguage->getHeader());
    }

    public function test_if_throws_exception_when_no_header_detected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot detect browser accepted language.');

        $browserLanguage = new BrowserLanguage();
    }

    public function test_if_allows_overriding_header_by_method(): void
    {
        $browserLanguage = new BrowserLanguage('test2');
        $browserLanguage->setHeader('test3');

        $this->assertSame('test3', $browserLanguage->getHeader());
    }

    public function test_if_handles_empty_header(): void
    {
        $browserLanguage = new BrowserLanguage('');

        $this->assertSame([], $browserLanguage->all());
        $this->assertSame(null, $browserLanguage->best());
    }

    public function test_if_parses_header(): void
    {
        $browserLanguage = new BrowserLanguage('en-US,en;q=0.5');

        $this->assertSame(['en-US', 'en'], $browserLanguage->all());
    }

    public function test_if_sorts_languages(): void
    {
        $browserLanguage = new BrowserLanguage('en;q=0.5,en-US');

        $this->assertSame(['en-US', 'en'], $browserLanguage->all());
    }

    public function test_if_preserves_order_of_languages_with_same_factor(): void
    {
        $browserLanguage = new BrowserLanguage('en-US,en;q=0.5,pl;q=0.5');

        $this->assertSame(['en-US', 'en', 'pl'], $browserLanguage->all());
    }

    public function test_if_omits_whitespaces(): void
    {
        $browserLanguage = new BrowserLanguage('en-US, en;q=0.7, pl;q=0.5');

        $this->assertSame(['en-US', 'en', 'pl'], $browserLanguage->all());
    }

    public function test_if_gives_correct_best_match(): void
    {
        $browserLanguage = new BrowserLanguage('en-US,en;q=0.5');

        $this->assertSame('en-US', $browserLanguage->best());
    }
}
