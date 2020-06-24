<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Utils;

use WS\Utils\Collections\HashCodeAware;

class TestInteger implements HashCodeAware
{
    /**
     * @var int
     */
    private $value;

    public static function of(int $value): TestInteger
    {
        return new self($value);
    }

    /**
     * TestInteger constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getHashCode(): string
    {
        $hash = 7;
        $hash = 31 * $hash + $this->value;
        return $hash.'';
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
