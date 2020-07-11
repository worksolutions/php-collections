<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use ReflectionProperty;
use RuntimeException;

class ObjectFunctions
{
    /**
     * @param $object
     * @param string $fieldName
     * @return mixed
     * @noinspection PhpDocMissingThrowsInspection
     */
    public static function getPropertyValue($object, string $fieldName)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        if (property_exists($object, $fieldName) && (new ReflectionProperty($object, $fieldName))->isPublic()) {
            return $object->{$fieldName};
        }

        if (method_exists($object, $fieldName)) {
            return $object->{$fieldName}();
        }

        if (method_exists($object, 'get'.$fieldName)) {
            return $object->{'get'.$fieldName}();
        }
        throw new RuntimeException("Field $fieldName is not exist for object ".var_export($object, true));
    }
}
