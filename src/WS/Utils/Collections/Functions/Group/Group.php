<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Group
{

    private $key;
    private $aggregators;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function __invoke(Collection $collection): array
    {
        $groupedResult = $this->group($collection);
        if (!$this->aggregators) {
            return $groupedResult;
        }
        return $this->applyAggregators($groupedResult);
    }

    private function group(Collection $collection): array
    {
        $result = [];
        foreach ($collection as $element) {
            if (!$groupKey = ObjectFunctions::getPropertyValue($element, $this->key)) {
                continue;
            }
            if (!isset($result[$groupKey])) {
                $result[$groupKey] = CollectionFactory::empty();
            }
            $result[$groupKey]->add($element);
        }
        return $result;
    }

    private function applyAggregators(array $groupedResult): array
    {
        $result = [];
        foreach ($groupedResult as $groupKey => $items) {
            foreach ($this->aggregators as $item) {
                [$destKey, $aggregator] = $item;
                $result[$groupKey][$destKey] = $aggregator($items);
            }
        }
        return $result;
    }

    /**
     * Create new instance of Group and use $key as group key
     * @param string $key
     * @return static
     */
    public static function by(string $key): self
    {
        return new self($key);
    }

    /**
     * Will calculate sum of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function sum(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Sum($sourceKey));
    }

    /**
     * Will calculate minimum value of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function min(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Min($sourceKey));
    }

    /**
     * Will calculate maximum value of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function max(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Max($sourceKey));
    }

    /**
     * Will calculate average value of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function avg(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Avg($sourceKey));
    }

    /**
     * Will find unique values of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function addToSet(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\AddToSet($sourceKey));
    }

    /**
     * Will return first value of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function first(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\First($sourceKey));
    }

    /**
     * Will return last value of items based on $sourceKey and put it to $destKey
     * @param string $sourceKey
     * @param string|null $destKey
     * @return $this
     */
    public function last(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Last($sourceKey));
    }

    /**
     * Will calculate count of items in group and put it to $destKey
     * @param string $destKey
     * @return $this
     */
    public function count(string $destKey): self
    {
        return $this->addAggregator($destKey, new Aggregator\Count());
    }

    /**
     * Add custom $aggregator with interface Aggregator\Aggregator|<Fn($c: Collection)>
     * @param string $destKey
     * @param callable $aggregator
     * @return $this
     */
    public function addAggregator(string $destKey, callable $aggregator): self
    {
        $this->aggregators[] = [$destKey, $aggregator];
        return $this;
    }

}
