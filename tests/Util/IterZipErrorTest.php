<?php

    namespace MathPHP\Tests\Util;

    use MathPHP\Util\Iter;
    use PHPUnit\Framework\TestCase;
    use stdClass;
    use TypeError;

    class IterZipErrorTest extends TestCase {
        /**
         * @return array
         */
        public static function dataProviderForNonIterables(): array
        {
            return [
                'int'    => [5],
                'float'  => [5.5],
                'string' => ['abc def'],
                'bool'   => [TRUE],
                'object' => [new stdClass()],
            ];
        }

        /**
         * @test         Zipping a non-iterable is a type error
         * @dataProvider dataProviderForNonIterables
         *
         * @param mixed $nonIterable
         */
        public function testNonIterableTypeError($nonIterable)
        {
            // Then
            $this->expectException(TypeError::class);

            // When
            Iter::zip($nonIterable);
        }

        /**
         * @test Nothing to iterate does nothing
         */
        public function testNothingToIterate()
        {
            // Given
            $nothing = [];
            $result = [];

            // When
            foreach (Iter::zip($nothing) as $_)
            {
                $result[] = $_;
            }

            // Then
            $this->assertEmpty($result);
        }
    }
