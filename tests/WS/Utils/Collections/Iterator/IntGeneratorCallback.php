<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

class IntGeneratorCallback
{

    /**
     * @var int
     */
    private $rest;
    /**
     * @var int
     */
    private $counter = 0;

    public function __construct(int $count)
    {
        $this->rest = $count;
    }

    public function __invoke()
    {
        $iterateResult = new IterateResult();
        if ($this->rest-- === 0) {
            $iterateResult->setAsRunOut();

            return $iterateResult;
        }

        return $iterateResult->setValue($this->counter++);
    }
}
