<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Expression\Operator;

class AndOperator extends AbstractOperator
{

    public function __invoke(bool $operand, $item): bool
    {
        if (!$operand) {
            return false;
        }
        $checker = $this->checker;
        return $checker($item);
    }

}
