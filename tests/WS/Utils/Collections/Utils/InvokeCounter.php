<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Utils;

class InvokeCounter
{
    private $calls = [];
    /**
     * @var callable|null
     */
    private $f;

    public function __construct(? callable $f = null)
    {
        $this->f = $f;
    }

    public function __invoke()
    {
        $this->calls[] = func_get_args();
        if ($this->f !== null) {
            return call_user_func_array($this->f, func_get_args());
        }
        return null;
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
