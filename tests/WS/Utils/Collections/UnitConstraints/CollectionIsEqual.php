<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\UnitConstraints;

use PHPUnit\Framework\Constraint\Constraint;
use WS\Utils\Collections\Collection;

class CollectionIsEqual extends Constraint implements StaticCompareCreation
{
    private $expectedCollection;

    public function __construct(Collection $expectedCollection)
    {
        $this->expectedCollection = $expectedCollection;
    }

    protected function matches($other): bool
    {
        if (!$other instanceof Collection) {
            throw new \RuntimeException('Value of comparision need to be a Collection');
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
