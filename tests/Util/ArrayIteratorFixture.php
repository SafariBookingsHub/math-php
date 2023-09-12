<?php

    namespace MathPHP\Tests\Util;

    use Iterator;
    use ReturnTypeWillChange;

    class ArrayIteratorFixture implements Iterator {

        private int $i;

        public function __construct(private array $values)
        {
            $this->i = 0;
        }

        public function rewind(): void
        {
            $this->i = 0;
        }

        #[ReturnTypeWillChange]
        public function current(): mixed
        {
            return $this->values[$this->i];
        }

        public function key(): int
        {
            return $this->i;
        }

        public function next(): void
        {
            ++$this->i;
        }

        public function valid(): bool
        {
            return isset($this->values[$this->i]);
        }
    }
