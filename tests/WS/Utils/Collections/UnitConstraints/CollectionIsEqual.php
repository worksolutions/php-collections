<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\UnitConstraints;

use PHPUnit\Framework\Constraint\Constraint;
use RuntimeException;
use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;

class CollectionIsEqual extends Constraint implements StaticCompareCreation
{
    private $expectedCollection;

    public function __construct($expectedCollection)
    {
        $this->expectedCollection = $this->normalize($expectedCollection);
    }

    private function normalize($collection)
    {
        if (is_array($collection)) {
            return CollectionFactory::from($collection);
        }

        return $collection;
    }

    protected function matches($other): bool
    {
        $this->normalize($other);
        if (!$other instanceof Collection) {
            throw new RuntimeException('Value of comparision need to be a Collection');
        }

        return $other->equals($this->expectedCollection);
    }

    public function toString(): string
    {
        return sprintf(
            'is accepted by %s',
            $this->exporter()->export($this->expectedCollection)
        );
    }

    public static function to($expectedValue): CollectionIsEqual
    {
        return new self($expectedValue);
    }
}
