<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

/**
 * Class MapFactory
 * @package WS\Utils\Collections
 */
class MapFactory
{
    /**
     * Creates Map from assoc array
     * @param array $assocArray
     * @return Map
     */
    public static function assoc(array $assocArray): Map
    {
        return self::fromIterable($assocArray);
    }

    public static function fromIterable(iterable $iterable): Map
    {
        $map = self::newObject();
        foreach ($iterable as $key => $value) {
            $map->put($key, $value);
        }

        return $map;
    }

    /**
     * Creates empty map object
     * @return Map
     */
    public static function emptyObject(): Map
    {
        return self::newObject();
    }

    /**
     * Creates empty Map object
     * @return Map
     */
    private static function newObject(): Map
    {
        return new HashMap();
    }
}
