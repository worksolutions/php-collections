<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Expression\Operator;

class OrOperator extends AbstractOperator
{

    public function __invoke(bool $operand, $item): bool
    {
        if ($operand) {
            return true;
        }
        $checker = $this->checker;
        return $checker($item);
    }

}
