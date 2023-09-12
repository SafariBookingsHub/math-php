<?php

    namespace MathPHP\Util;

    use ArrayIterator;
    use Iterator;
    use IteratorIterator;
    use LogicException;
    use MultipleIterator;
    use Traversable;

    use function gettype;
    use function is_array;

    /**
     * @internal
     */
    class Iter {
        /**
         * Zip - Make an iterator that aggregates items from multiple iterators
         * Similar to Python's zip function
         *
         * @param array ...$iterables
         *
         * @return \MultipleIterator
         * @internal
         *
         */
        public static function zip(iterable ...$iterables): MultipleIterator
        {
            $zippedIterator = new MultipleIterator();
            foreach ($iterables as $iterable)
                $zippedIterator->attachIterator(self::makeIterator($iterable));

            return $zippedIterator;
        }

        /**
         * @param array $iterable
         *
         * @return \Iterator
         */
        private static function makeIterator(iterable $iterable): Iterator
        {
            switch (TRUE)
            {
                case $iterable instanceof Iterator:
                    return $iterable;

                case $iterable instanceof Traversable:
                    return new IteratorIterator($iterable);

                case is_array($iterable):
                    return new ArrayIterator($iterable);
            }

            throw new LogicException(gettype($iterable)
                .' type is not an expected iterable type (Iterator|Traversable|array)');
        }
    }
