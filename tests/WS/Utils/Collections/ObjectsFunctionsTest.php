<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use WS\Utils\Collections\Functions\ObjectFunctions;
use WS\Utils\Collections\Utils\ExampleObject;

class ObjectsFunctionsTest extends TestCase
{
    /**
     * @test
     */
    public function gettingPropertyValue(): void
    {
        $o = new ExampleObject();
        $o->property = 'value';
        $o->setName('nameValue');
        $o->setField('fieldValue');

        $this->assertEquals('value', ObjectFunctions::getPropertyValue($o, 'property'));
        $this->assertEquals('nameValue', ObjectFunctions::getPropertyValue($o, 'name'));
        $this->assertEquals('fieldValue', ObjectFunctions::getPropertyValue($o, 'field'));

        $this->expectException(RuntimeException::class);
        ObjectFunctions::getPropertyValue($o, 'undefinedProperty');
    }

    /**
     * @test
     */
    public function gettingArrayFieldValue()
    {
        $array = ['a' => 1, 'b' => 2];

        $this->assertEquals(1, ObjectFunctions::getPropertyValue($array, 'a'));
        $this->assertEquals(2, ObjectFunctions::getPropertyValue($array, 'b'));

        $this->assertNull(ObjectFunctions::getPropertyValue($array, 'c'));
    }
}
