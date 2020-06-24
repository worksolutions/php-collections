<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

use RuntimeException;

class CallbackIterator implements Iterator
{
    /**
     * @var IterateResult
     */
    private $currentIterateResult;
    private $generator;

    /**
     * CallbackIterator constructor.
     * @param $f callable Function with interface Fun(): IterateResult
     */
    public function __construct(callable $f)
    {
        $this->generator = $f;
    }

    public function next()
    {
        if (!$this->hasNext()) {
            throw new RuntimeException('Iterated element is absent');
        }
        $value = $this->currentIterateResult->getValue();
        $this->currentIterateResult = null;
        return $value;
    }

    public function hasNext(): bool
    {
        if ($this->currentIterateResult === null) {
            $f = $this->generator;
            $this->currentIterateResult = $f();
        }
        return $this->currentIterateResult->isRunOut() === false;
    }
}
