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

    public function __invoke(Collection $collection)
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

    public static function by(string $key): self
    {
        return new self($key);
    }

    public function sum(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Sum($sourceKey));
    }

    public function min(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Min($sourceKey));
    }

    public function max(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Max($sourceKey));
    }

    public function avg(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Avg($sourceKey));
    }

    public function addToSet(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\AddToSet($sourceKey));
    }

    public function first(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\First($sourceKey));
    }

    public function last(string $sourceKey, string $destKey = null): self
    {
        return $this->addAggregator($destKey ?? $sourceKey, new Aggregator\Last($sourceKey));
    }

    public function count(string $destKey): self
    {
        return $this->addAggregator($destKey, new Aggregator\Count());
    }

    public function addAggregator(string $destKey, callable $aggregator): self
    {
        $this->aggregators[] = [$destKey, $aggregator];
        return $this;
    }

}
