<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface HashCodeAware
{
    public function getHashCode(): string;
}
