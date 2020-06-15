<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use WS\Utils\Collections\Collection;

class Predicates
{
    public static function notNull(): callable
    {
        return static function ($el): bool {
            return $el !== null;
        };
    }

    public static function notResistance(): callable
    {
        return static function (): bool {
            return true;
        };
    }

    public static function lock(): callable
    {
        return static function (): bool {
            return false;
        };
    }

    public static function random(int $count): callable
    {
        return new class($count) implements CollectionAwareFunction
        {
            private $f;

            /**
             * @var int
             */
            private $count;

            public function __construct(int $count)
            {
                $this->count = $count;
                $this->f = Predicates::notResistance();
                if ($count === 0) {
                    $this->f = Predicates::lock();
                }
            }

            public function withCollection(Collection $collection): void
            {
                $collectionSize = $collection->size();
                $expectedCount = $this->count;

                if ($expectedCount === 0) {
                    return;
                }

                if ($collectionSize < $expectedCount) {
                    return;
                }

                $rest = $collectionSize;
                $generated = 0;
                $multiplicity = (int)round($collectionSize / $expectedCount);

                $fRandomizer = static function () use ($multiplicity): int {
                    return random_int(0, $multiplicity - 1);
                };

                $this->f = static function () use (& $generated, & $rest, $expectedCount, $fRandomizer): bool {
                    $rest--;
                    if ($generated === $expectedCount) {
                        return false;
                    }
                    if ($generated + $rest + 1 <= $expectedCount) {
                        return true;
                    }
                    if ($fRandomizer() !== 0) {
                        return false;
                    }
                    $generated++;
                    return true;
                };
            }

            public function __invoke($el): bool
            {
                $fun = $this->f;
                return $fun($el);
            }
        };
    }
}
