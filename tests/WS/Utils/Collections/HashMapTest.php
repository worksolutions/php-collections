<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;

class HashMapTest extends TestCase
{
    use MapInterfaceTest;

    private function createInstance(): Map
    {
        return new HashMap();
    }
}
