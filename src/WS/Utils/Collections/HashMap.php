<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 * @license MIT
 */

namespace WS\Utils\Collections;

use ArrayIterator;
use RuntimeException;

class HashMap implements Map
{
    private $entries = [];

    public function put($key, $value): bool
    {
        $this->entries[$this->getKeyHash($key)] = new MapEntry($key, $value);

        return true;
    }

    public function getIterator()
    {
        return new ArrayIterator(array_map(static function (MapEntry $entry) {
            return $entry->getValue();
        }, $this->entries));
    }

    public function values(): Collection
    {
        $values = [];
        /** @var MapEntry $entry */
        foreach ($this->entries as $entry) {
            $values[] = $entry->getValue();
        }
        return new ArrayList($values);
    }

    public function keys(): Collection
    {
        $keys = [];
        /** @var MapEntry $entry */
        foreach ($this->entries as $entry) {
            $keys[] = $entry->getKey();
        }
        return new ArrayList($keys);
    }

    private function getKeyHash($key): string
    {
        if (is_scalar($key)) {
            return $key.'';
        }
        if ($key instanceof HashCodeAware) {
            return $key->getHashCode();
        }
        if (is_object($key)) {
            return spl_object_hash($key);
        }
        if ($key === null) {
            return '__NULL__';
        }
        if (is_array($key)) {
            return md5(json_encode($key));
        }
        throw new RuntimeException("The type of $key is not supported");
    }

    public function remove($key): bool
    {
        $res = $this->containsKey($key);

        if (!$res) {
            return false;
        }
        unset($this->entries[$this->getKeyHash($key)]);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function containsKey($key): bool
    {
        return isset($this->entries[$this->getKeyHash($key)]);
    }

    public function size(): int
    {
        return count($this->entries);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        if (!$this->containsKey($key)) {
            return null;
        }

        $entry = $this->entries[$this->getKeyHash($key)];
        return $entry->getValue();
    }

    /**
     * @inheritDoc
     */
    public function containsValue($tested): bool
    {
        foreach ($this->entries as $entry) {
            if ($entry->getValue() === $tested) {
                return true;
            }
        }
        return false;
    }
}
