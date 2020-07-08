<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Converters;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\ExampleObject;

class ConvertersFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;

    /**
     * @test
     */
    public function propertyValueConverting(): void
    {
        $collection = self::toCollection(
                (new ExampleObject())->setName('first'),
                (new ExampleObject())->setName('second'),
                (new ExampleObject())->setName('third')
            )
            ->stream()
            ->map(Converters::toPropertyValue('name'))
            ->getCollection()
        ;

        $this->assertThat($collection, CollectionIsEqual::to(['first', 'second', 'third']));
    }

    /**
     * @test
     */
    public function propertyValueAssocConverting(): void
    {
        $array = self::toCollection(
            (new ExampleObject())->setName('first')->setField('f1'),
            (new ExampleObject())->setName('second')->setField('f2'),
            (new ExampleObject())->setName('third')->setField('f3')
        )
            ->stream()
            ->map(Converters::toProperties(['name', 'field']))
            ->getCollection()
            ->toArray();

        $this->assertSame($array, [
            ['name' => 'first', 'field' => 'f1'],
            ['name' => 'second', 'field' => 'f2'],
            ['name' => 'third', 'field' => 'f3']
        ]);
    }
}
