<?php

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
        $groupedResult = [];
        foreach ($collection as $element) {
            if (!$groupKey = ObjectFunctions::getPropertyValue($element, $this->key)) {
                continue;
            }
            if (!isset($groupedResult[$groupKey])) {
                $groupedResult[$groupKey] = CollectionFactory::empty();
            }
            $groupedResult[$groupKey]->add($element);
        }
        if (!$this->aggregators) {
            return $groupedResult;
        }
        $aggregatedResult = [];
        foreach ($groupedResult as $groupKey => $items) {
            foreach ($this->aggregators as $item) {
                [$destKey, $aggregator] = $item;
                $aggregatedResult[$groupKey][$destKey] = $aggregator($items);
            }
        }
        return $aggregatedResult;
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
