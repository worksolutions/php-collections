<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Expression\Operator;

abstract class AbstractOperator
{
    protected $checker;

    public function __construct(callable $checker)
    {
        $this->checker = $checker;
    }

    public function getChecker(): callable
    {
        return $this->checker;
    }

    abstract public function __invoke(bool $operand, $item): bool;

}
