<?php
declare(strict_types=1);

namespace Dartui\BrowserLanguage;

use InvalidArgumentException;

class BrowserLanguage
{
    /**
     * The Accept-Language header value.
     *
     * @var  string
     */
    protected $header;

    /**
     * The browser language detector constructor.
     *
     * @param  string|null  $header
     */
    public function __construct(string $header = null)
    {
        if ($header !== null) {
            $this->header = $header;
        } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        } else {
            throw new InvalidArgumentException('Cannot detect browser accepted language.');
        }
    }

    /**
     * Set the Accept-Language header value.
     *
     * @param  string  $header
     * @return void
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * Get the Accept-Language header value.
     *
     * @return  string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * Get the list of all languages understandable by user ordered by factors.
     *
     * @return  array<string>
     */
    public function all(): array
    {
        if (empty($this->header)) {
            return [];
        }

        $languages = $this->parseHeader($this->header);
        $languages = $this->sortLanguages($languages);

        return array_map(function (Language $languages) {
            return $languages->getName();
        }, $languages);
    }

    /**
     * Get the best understandable language of user.
     *
     * @return  string|null
     */
    public function best(): ?string
    {
        /** Return first language from all matches. */
        foreach ($this->all() as $language) {
            return $language;
        }

        return null;
    }

    /**
     * Parse the header to list of languages.
     *
     * @param   string  $header
     * @return  array<\Dartui\BrowserLanguage\Language>
     */
    protected function parseHeader(string $header): array
    {
        $order = 1;

        return array_map(function (string $locale) use (&$order) {
            return $this->parseLanguage($locale, $order++);
        }, explode(',', $header));
    }

    /**
     * Parse part of the header to instance of language.
     *
     * @param   string  $locale
     * @param   int     $order
     * @return  \Dartui\BrowserLanguage\Language
     */
    protected function parseLanguage(string $locale, int $order): Language
    {
        return Language::fromString($locale, $order++);
    }

    /**
     * Sort the list of languages using factors.
     *
     * @param   array<\Dartui\BrowserLanguage\Language>   $languages
     * @return  array<\Dartui\BrowserLanguage\Language>
     */
    protected function sortLanguages(array $languages): array
    {
        usort($languages, function (Language $a, Language $b) {
            return $a->toCompareableData() <=> $b->toCompareableData();
        });

        return $languages;
    }
}
