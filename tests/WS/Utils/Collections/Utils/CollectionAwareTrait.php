<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Utils;

use WS\Utils\Collections\ArrayList;
use WS\Utils\Collections\Collection;

trait CollectionAwareTrait
{
    private function toCollection(?array $data): Collection
    {
        return new ArrayList($data);
    }
}
