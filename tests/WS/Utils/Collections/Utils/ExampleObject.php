<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Utils;

class ExampleObject
{
    public $property;
    private $name = 'Example';
    private $field = 'field';

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function field(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }
}
