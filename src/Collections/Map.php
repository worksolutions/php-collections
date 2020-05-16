<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use IteratorAggregate;

interface Map extends IteratorAggregate
{
    public function put($key, $value): bool;

    public function keySet(): Set;

    public function values(): Collection;
}
