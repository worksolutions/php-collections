<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use WS\Utils\Collections\Collection;

interface CollectionAwareFunction
{
    public function withCollection(Collection $collection): void;

    public function __invoke($el): bool;
}
