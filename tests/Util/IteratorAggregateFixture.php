<?php

    namespace MathPHP\Tests\Util;

    use ArrayIterator;
    use IteratorAggregate;

    class IteratorAggregateFixture implements IteratorAggregate {

        public function __construct(private array $values)
        {
        }

        public function getIterator(): ArrayIterator
        {
            return new ArrayIterator($this->values);
        }
    }
