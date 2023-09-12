<?php

    namespace MathPHP\Tests\Util;

    use Iterator;
    use ReturnTypeWillChange;

    class ArrayIteratorFixture implements Iterator {
        /** @var array */
        private array $values;

        /** @var int */
        private int $i;

        public function __construct(array $values)
        {
            $this->values = $values;
            $this->i = 0;
        }

        public function rewind(): void
        {
            $this->i = 0;
        }

        /**
         * @return mixed
         */
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
