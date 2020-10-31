<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Expression;

use WS\Utils\Collections\Functions\Expression\Operator\AbstractOperator;
use WS\Utils\Collections\Functions\Expression\Operator\AndOperator;
use WS\Utils\Collections\Functions\Expression\Operator\OrOperator;

class BoolExpression
{

    /** @var AbstractOperator[] */
    private $operators = [];

    public function __construct(callable $checker)
    {
        $this->operators[] = new AndOperator($checker);
    }

    public static function with(callable $checker): self
    {
        return new self($checker);
    }

    public function __invoke($item): bool
    {
        $operand = null;
        foreach ($this->operators as $operator) {
            if ($operand === null) {
                if (!$operand = $operator->getChecker()($item)) {
                    return false;
                }
                continue;
            }
            if (!$operand = $operator($operand, $item)) {
                return false;
            }
        }
        return true;
    }

    public function and(callable $checker): self
    {
        $this->operators[] = new AndOperator($checker);
        return $this;
    }

    public function or(callable $checker): self
    {
        $this->operators[] = new OrOperator($checker);
        return $this;
    }

}
