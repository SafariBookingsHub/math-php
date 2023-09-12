<?php

    namespace MathPHP\Tests\Util;

    use ArrayIterator;
    use IteratorAggregate;

    class IteratorAggregateFixture implements IteratorAggregate {
        /** @var array */
        private array $values;

        public function __construct(array $values)
        {
            $this->values = $values;
        }

        public function getIterator(): ArrayIterator
        {
            return new ArrayIterator($this->values);
        }
    }
