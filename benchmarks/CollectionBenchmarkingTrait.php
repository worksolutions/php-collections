<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/**
 * @author Maxim Sokolovsky
 */

namespace Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Assert;
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
     * @Assert("10")
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
     * @Assert("10")
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
     * @Assert("5")
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
     * @Assert("10")
     */
    public function clearing(): void
    {
        $this->getBigCollection()->clear();
    }

    /**
     * @Subject
     * @Assert("5")
     */
    public function copying(): void
    {
        $this->getBigCollection()->copy();
    }

    /**
     * @Subject
     * @Assert("10")
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
     * @Assert("10")
     */
    public function merging(): void
    {
        $this->getBigCollection()->merge($this->getSmallCollection());
    }

    /**
     * @Subject
     * @Assert("50")
     */
    public function containsChecking(): void
    {
        $this->getBigCollection()->contains(50000);
    }

    /**
     * @Subject
     * @Assert("20")
     */
    public function equalsChecking(): void
    {
        $copy = $this->getBigCollection()->copy();
        $this->getBigCollection()->equals($copy);
    }

    /**
     * @Subject
     * @Assert("5")
     */
    public function sizeChecking(): void
    {
        $this->getBigCollection()->size();
    }

    /**
     * @Subject
     * @Assert("5")
     */
    public function emptinessChecking(): void
    {
        $this->getBigCollection()->isEmpty();
    }

    /**
     * @Subject
     * @Assert("60")
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

    /**
     * @Subject
     * @Assert("20")
     */
    public function streamGetting(): void
    {
        $this->getBigCollection()->stream();
    }

    /**
     * @Subject
     * @Assert("10")
     */
    public function arrayConverting(): void
    {
        $this->getBigCollection()->toArray();
    }
}
