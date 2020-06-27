<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use WS\Utils\Collections\Iterator\Iterator;

interface IndexIterable
{
    public function getIndexIterator(): Iterator;
}
