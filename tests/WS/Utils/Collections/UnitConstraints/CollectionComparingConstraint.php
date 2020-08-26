<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\UnitConstraints;

use PHPUnit\Framework\Constraint\Constraint;
use RuntimeException;
use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;

abstract class CollectionComparingConstraint extends Constraint implements StaticCompareCreation
{
    private $expectedCollection;

    public function __construct($expectedCollection)
    {
        parent::__construct();
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

        return $this->comparingResult($this->expectedCollection, $other);
    }

    public function toString(): string
    {
        return sprintf(
            'is accepted by %s',
            $this->exporter->export($this->expectedCollection)
        );
    }

    public static function to($expectedValue): CollectionComparingConstraint
    {
        return new static($expectedValue);
    }

    abstract public function comparingResult(Collection $expectedCollection, Collection $other): bool;
}
