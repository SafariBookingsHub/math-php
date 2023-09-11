<?php

    namespace MathPHP\Tests\Util;

    use Generator;

    class GeneratorFixture {
        public static function getGenerator(array $values): Generator
        {
            foreach ($values as $value)
            {
                yield $value;
            }
        }
    }
