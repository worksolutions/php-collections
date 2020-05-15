<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use IteratorAggregate;

interface Map extends IteratorAggregate
{
    public function add($key, $value): bool;
}
