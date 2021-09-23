<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;

class ObjectFunctions
{
    private static $getters = [];

    /**
     * @param $object
     * @param string $fieldName
     * @return mixed
     */
    public static function getPropertyValue($object, string $fieldName)
    {
        if (is_array($object)) {
            return $object[$fieldName] ?? null;
        }

        if (!is_object($object)) {
            throw new RuntimeException('Type of $object need to be array or object');
        }
        $getter = self::getObjectPropertyGetter(get_class($object), $fieldName);
        return $getter($object);
    }

    private static function getObjectPropertyGetter(string $class, string $fieldName): \Closure
    {
        $getterName = $class . '::' . $fieldName;
        if (isset(self::$getters[$getterName])) {
            return self::$getters[$getterName];
        }

        if (method_exists($class, $fieldName)) {
            self::$getters[$getterName] = static function ($object) use ($fieldName) {
                return $object->{$fieldName}();
            };
        }
        if (method_exists($class, 'get'.$fieldName)) {
            self::$getters[$getterName] = static function ($object) use ($fieldName) {
                return $object->{'get'.$fieldName}();
            };
        }

        if (isset($object->{$fieldName})) {
            self::$getters[$getterName] = static function ($object) use ($fieldName) {
                return $object->{$fieldName};
            };
        }
        try {
            $refProperty = new ReflectionProperty($class, $fieldName);
            $refProperty->setAccessible(true);
            self::$getters[$getterName] = static function ($object) use ($refProperty) {
                return $refProperty->getValue($object);
            };
        } catch (ReflectionException $exception) {
        }
        if (!isset(self::$getters[$getterName])) {
            throw new RuntimeException("Field $fieldName is not exist for class ".var_export($class, true));
        }
        return self::$getters[$getterName];
    }
}
