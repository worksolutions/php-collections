<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

trait CollectionConstructorTrait
{
    private static function toCollection(...$elements): Collection
    {
        return new ArrayList($elements);
    }
}
