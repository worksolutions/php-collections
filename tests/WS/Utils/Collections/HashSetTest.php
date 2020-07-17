<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;

class HashSetTest extends TestCase
{
    use SetInterfaceTestTrait;
    use CollectionInterfaceTestTrait;

    protected function createInstance(...$args): Set
    {
        return new HashSet($args);
    }
}
