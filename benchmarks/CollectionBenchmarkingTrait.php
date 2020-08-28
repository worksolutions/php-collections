<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/**
 * @author Maxim Sokolovsky
 */

namespace Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Subject;
use WS\Utils\Collections\Collection;

trait CollectionBenchmarkingTrait
{
    abstract protected function getSmallArray(): array;

    abstract protected function getBigArray(): array;

    /**
     * @return Collection
     */
    abstract protected function getBigCollection();

    /**
     * @return Collection
     */
    abstract protected function getSmallCollection();

    /**
     * @param array $elements
     * @return Collection
     */
    abstract protected function constructor(array $elements);

    /**
     * @Subject
     */
    public function emptyConstructing(): void
    {
        $this->constructor([]);
    }

    /**
     * @Subject
     */
    public function emptyArrayConstructing(): void
    {
        $ar = [];
    }

    /**
     * @Subject
     */
    public function collectionWithValuesConstructing(): void
    {
        $this->constructor($this->getBigArray());
    }

    /**
     * @Subject
     */
    public function arrayWithValuesConstructing(): void
    {
        $ar = $this->getBigArray();
    }

    /**
     * @Subject()
     */
    public function adding(): void
    {
        $this->getBigCollection()->add(10);
    }

    /**
     * @Subject()
     */
    public function addingInArray(): void
    {
        $this->getBigArray()[] = 10;
    }


    /**
     * @Subject
     */
    public function clearing(): void
    {
        $this->getBigCollection()->clear();
    }

    /**
     * @Subject
     */
    public function copying(): void
    {
        $this->getBigCollection()->copy();
    }

    /**
     * @Subject
     */
    public function addingAll(): void
    {
        $this->getBigCollection()->addAll($this->getSmallArray());
    }

    /**
     * @Subject
     */
    public function arrayAddingAll(): void
    {
        array_merge($this->getBigArray(), $this->getSmallArray());
    }

    /**
     * @Subject
     */
    public function merging(): void
    {
        $this->getBigCollection()->merge($this->getSmallCollection());
    }

    /**
     * @Subject
     */
    public function containsChecking(): void
    {
        $this->getBigCollection()->contains(50000);
    }

    /**
     * @Subject
     */
    public function equalsChecking(): void
    {
        $copy = $this->getBigCollection()->copy();
        $this->getBigCollection()->equals($copy);
    }

    /**
     * @Subject
     */
    public function sizeChecking(): void
    {
        $this->getBigCollection()->size();
    }

    /**
     * @Subject
     */
    public function emptinessChecking(): void
    {
        $this->getBigCollection()->isEmpty();
    }

    /**
     * @Subject
     */
    public function removing(): void
    {
        $this->getBigCollection()->remove(100);
    }

    /**
     * @Subject
     */
    public function removingFromArray(): void
    {
        array_diff($this->getBigArray(), [100]);
    }
}
