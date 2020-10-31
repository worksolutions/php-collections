<?php

namespace WS\Utils\Collections\Functions\Group;

class Group
{

    private $aggregators;

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

    private function addAggregator(string $destKey, callable $aggregator)
    {
        $this->aggregators[] = [$destKey, $aggregator];
        return $this;
    }

}
