<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Utils;

class InvokesCounter
{
    private $calls = [];

    public function __invoke()
    {
        $this->calls[] = func_get_args();
    }

    public function countOfInvokes(): int
    {
        return count($this->calls);
    }

    public function calls(): array
    {
        return $this->calls;
    }
}
