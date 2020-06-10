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

    public function __construct(int $value)
    {
        $this->value = $value;
    }
    public function getHashCode(): string
    {
        $hash = 7;
        $hash = 31 * $hash + $this->value;
        return $hash.'';
    }
}
