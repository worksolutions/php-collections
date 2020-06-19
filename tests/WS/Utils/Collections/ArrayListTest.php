<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase
{
    use CollectionInterfaceTestTrait;
    use ListInterfaceTestTrait;

    public function createInstance(...$args): Collection
    {
        return ArrayList::of(...$args);
    }
}
