<?php
/** @noinspection ClassReImplementsParentInterfaceInspection */

/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

class HashSet implements Set
{
    /**
     * @var HashMap
     */
    private $internalMap;
    public function __construct(?array $elements = null)
    {
        $this->clear();
        if ($elements !== null) {
            foreach ($elements as $element) {
                $this->add($element);
            }
        }
    }

    public function add($element): bool
    {
        return $this->internalMap->put($element, null);
    }

    public function stream(): Stream
    {
        return new SerialStream($this);
    }

    public function merge(Collection $collection): bool
    {
        foreach ($collection as $item) {
            $this->add($item);
        }
        return true;
    }

    public function clear(): void
    {
        $this->internalMap = new HashMap();
    }

    public function remove($element): bool
    {
        return $this->internalMap->remove($element);
    }

    public function contains($element): bool
    {
        return $this->internalMap->containsKey($element);
    }

    public function equals(Collection $collection): bool
    {
        return $this->toArray() === $collection->toArray();
    }

    public function size(): int
    {
        return $this->internalMap->size();
    }

    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    public function toArray(): array
    {
        return $this->internalMap->values()->toArray();
    }

    public function copy(): Collection
    {
        return new static($this->toArray());
    }

    public function getIterator()
    {
        return $this->internalMap->values();
    }
}