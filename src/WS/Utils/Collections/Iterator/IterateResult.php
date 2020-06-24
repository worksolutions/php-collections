<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

class IterateResult
{
    private $value;
    private $isRunOut = false;

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setAsRunOut(): self
    {
        $this->isRunOut = true;

        return $this;
    }

    public function isRunOut(): bool
    {
        return $this->isRunOut;
    }

    public function getValue()
    {
        return $this->value;
    }
}