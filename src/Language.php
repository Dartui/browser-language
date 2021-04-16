<?php
declare(strict_types=1);

namespace Dartui\BrowserLanguage;

use InvalidArgumentException;

class Language
{
    /**
     * The name of language locale.
     *
     * @var  string
     */
    protected $name;

    /**
     * The factor of language locale.
     *
     * @var  float
     */
    protected $factor;

    /**
     * The order of language locale.
     *
     * @var  int
     */
    protected $order;

    /**
     * The single browser language constructor.
     *
     * @param  string  $name
     * @param  float   $factor
     * @param  int     $order
     */
    final public function __construct(string $name, float $factor = 1.0, int $order = 1)
    {
        $this->name   = $name;
        $this->factor = $factor;
        $this->order  = $order;
    }

    /**
     * Parse part of the header to instance of language.
     *
     * @param   string  $locale
     * @param   int     $order
     * @return  \Dartui\BrowserLanguage\Language
     */
    public static function fromString(string $locale, int $order = 1): Language
    {
        $pattern = '/^\s*(?P<name>\*|[a-zA-Z\-]+)(?:;q=(?P<factor>1|0.\d+))?\s*$/';

        preg_match($pattern, $locale, $matches);

        if (empty($matches['name'])) {
            throw new InvalidArgumentException('Cannot match name in given string.');
        }

        $name   = $matches['name'];
        $factor = (float) ($matches['factor'] ?? 1);

        return new static($name, $factor, $order);
    }

    /**
     * Get the name of language locale.
     *
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the factor of language locale.
     *
     * @return  float
     */
    public function getFactor(): float
    {
        return $this->factor;
    }

    /**
     * Get the order of language locale.
     *
     * @return  int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Convert language to compareable string.
     *
     * Factor of language is clamped to three digits after decimal according to RFC.
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.9
     *
     * @return  string
     */
    public function toCompareableData(): string
    {
        return sprintf('%.3f_%d', 1 - $this->getFactor(), $this->getOrder());
    }
}
