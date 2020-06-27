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
}
