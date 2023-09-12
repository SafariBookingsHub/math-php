<?php

    namespace MathPHP\Tests\Functions\Map;

    use MathPHP\Exception;
    use MathPHP\Functions\Map\Single;
    use PHPUnit\Framework\TestCase;

    class SingleTest extends TestCase {
        public static function dataProviderForSquare(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [1, 4, 9, 16],
                ],
                [
                    [7, 8, 9, 10],
                    [49, 64, 81, 100],
                ],
            ];
        }

        public static function dataProviderForCube(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [1, 8, 27, 64],
                ],
                [
                    [7, 8, 9, 10],
                    [343, 512, 729, 1000],
                ],
            ];
        }

        public static function dataProviderForPow(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    5,
                    [1, 32, 243, 1024],
                ],
                [
                    [7, 8, 9, 10],
                    4,
                    [2401, 4096, 6561, 10000],
                ],
            ];
        }

        public static function dataProviderForSqrt(): array
        {
            return [
                [
                    [4, 9, 16, 25],
                    [2, 3, 4, 5],
                ],
                [
                    [64, 81, 100, 144],
                    [8, 9, 10, 12],
                ],
            ];
        }

        public static function dataProviderForAbs(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [1, 2, 3, 4],
                ],
                [
                    [1, -2, 3, -4],
                    [1, 2, 3, 4],
                ],
                [
                    [-1, -2, -3, -4],
                    [1, 2, 3, 4],
                ],
            ];
        }

        public static function dataProviderForAdd(): array
        {
            return [
                [[1, 2, 3, 4, 5], 4, [5, 6, 7, 8, 9]],
                [[5, 7, 23, 5, 2], 9.1, [14.1, 16.1, 32.1, 14.1, 11.1]],
            ];
        }

        public static function dataProviderForSubtract(): array
        {
            return [
                [[1, 2, 3, 4, 5], 1, [0, 1, 2, 3, 4]],
                [[5, 7, 23, 5, 2], 3, [2, 4, 20, 2, -1]],
            ];
        }

        public static function dataProviderForMultiply(): array
        {
            return [
                [[1, 2, 3, 4, 5], 4, [4, 8, 12, 16, 20]],
                [[5, 7, 23, 5, 2], 3, [15, 21, 69, 15, 6]],
            ];
        }

        public static function dataProviderForDivide(): array
        {
            return [
                [[1, 2, 3, 4, 5], 2, [0.5, 1, 1.5, 2, 2.5]],
                [[5, 10, 15, 20, 25], 5, [1, 2, 3, 4, 5]],
            ];
        }

        public static function dataProviderForMax(): array
        {
            return [
                [[1, 2, 3, 4, 5], 0, [1, 2, 3, 4, 5]],
                [[1, 2, 3, 4, 5], 1, [1, 2, 3, 4, 5]],
                [[1, 2, 3, 4, 5], 3, [3, 3, 3, 4, 5]],
                [[1, 2, 3, 4, 5], 6, [6, 6, 6, 6, 6]],
                [[1, 2, 3, 4, 5], 9, [9, 9, 9, 9, 9]],
                [[1, 2, 3, 4, 5], 3.4, [3.4, 3.4, 3.4, 4, 5]],
                [[1, 2, 3, 4, 5], 6.7, [6.7, 6.7, 6.7, 6.7, 6.7]],
            ];
        }

        public static function dataProviderForMin(): array
        {
            return [
                [[1, 2, 3, 4, 5], 0, [0, 0, 0, 0, 0]],
                [[1, 2, 3, 4, 5], 1, [1, 1, 1, 1, 1]],
                [[1, 2, 3, 4, 5], 3, [1, 2, 3, 3, 3]],
                [[1, 2, 3, 4, 5], 6, [1, 2, 3, 4, 5]],
                [[1, 2, 3, 4, 5], 9, [1, 2, 3, 4, 5]],
                [[1, 2, 3, 4, 5], 3.4, [1, 2, 3, 3.4, 3.4]],
                [[1, 2, 3, 4, 5], 6.7, [1, 2, 3, 4, 5]],
            ];
        }

        public static function dataProviderForReciprocal(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [1, 1 / 2, 1 / 3, 1 / 4],
                ],
                [
                    [7, 8, 9, 10],
                    [1 / 7, 1 / 8, 1 / 9, 1 / 10],
                ],
                [
                    [-2, -1, 1.1, 2.5, 6.73],
                    [-1 / 2, -1, 1 / 1.1, 1 / 2.5, 1 / 6.73],
                ],
            ];
        }

        /**
         * @test         square
         * @dataProvider dataProviderForSquare
         *
         * @param array $xs
         * @param array $expected
         */
        public function testSquare(array $xs, array $expected)
        {
            // When
            $squares = Single::square($xs);

            // Then
            $this->assertEquals($expected, $squares);
        }

        /**
         * @test         cube
         * @dataProvider dataProviderForCube
         *
         * @param array $xs
         * @param array $expected
         */
        public function testCube(array $xs, array $expected)
        {
            // When
            $cubes = Single::cube($xs);

            // Then
            $this->assertEquals($expected, $cubes);
        }

        /**
         * @test         pow
         * @dataProvider dataProviderForPow
         *
         * @param array $xs
         * @param int   $n
         * @param array $expected
         */
        public function testPow(array $xs, int $n, array $expected)
        {
            // When
            $pows = Single::pow($xs, $n);

            // Then
            $this->assertEquals($expected, $pows);
        }

        /**
         * @test         equals
         * @dataProvider dataProviderForSqrt
         *
         * @param array $xs
         * @param array $expected
         */
        public function testSqrt(array $xs, array $expected)
        {
            // When
            $sqrts = Single::sqrt($xs);

            // Then
            $this->assertEquals($expected, $sqrts);
        }

        /**
         * @test         equals
         * @dataProvider dataProviderForAbs
         *
         * @param array $xs
         * @param array $expected
         */
        public function testAbs(array $xs, array $expected)
        {
            // When
            $abs = Single::abs($xs);

            // Then
            $this->assertEquals($expected, $abs);
        }

        /**
         * @test         add
         * @dataProvider dataProviderForAdd
         *
         * @param array $xs
         * @param mixed $k
         * @param array $expected
         */
        public function testAdd(array $xs, mixed $k, array $expected)
        {
            // When
            $sums = Single::add($xs, $k);

            // Then
            $this->assertEquals($expected, $sums);
        }

        /**
         * @test         subtract
         * @dataProvider dataProviderForSubtract
         *
         * @param array $xs
         * @param int   $k
         * @param array $expected
         */
        public function testSubtract(array $xs, int $k, array $expected)
        {
            // When
            $differences = Single::subtract($xs, $k);

            // Then
            $this->assertEquals($expected, $differences);
        }

        /**
         * @test         multiply
         * @dataProvider dataProviderForMultiply
         *
         * @param array $xs
         * @param int   $k
         * @param array $expected
         */
        public function testMultiply(array $xs, int $k, array $expected)
        {
            // When
            $products = Single::multiply($xs, $k);

            // Then
            $this->assertEquals($expected, $products);
        }

        /**
         * @test         multiply
         * @dataProvider dataProviderForDivide
         *
         * @param array $xs
         * @param int   $k
         * @param array $expected
         */
        public function testDivide(array $xs, int $k, array $expected)
        {
            // When
            $quotients = Single::divide($xs, $k);

            // Then
            $this->assertEquals($expected, $quotients);
        }

        /**
         * @test         max
         * @dataProvider dataProviderForMax
         *
         * @param array $xs
         * @param mixed $value
         * @param array $expected
         */
        public function testMax(array $xs, mixed $value, array $expected)
        {
            // When
            $maxes = Single::max($xs, $value);

            // Then
            $this->assertEquals($expected, $maxes);
        }

        /**
         * @test         min
         * @dataProvider dataProviderForMin
         *
         * @param array $xs
         * @param mixed $value
         * @param array $expected
         */
        public function testMin(array $xs, mixed $value, array $expected)
        {
            // When
            $mins = Single::min($xs, $value);

            // Then
            $this->assertEquals($expected, $mins);
        }

        /**
         * @test         reciprocal
         * @dataProvider dataProviderForReciprocal
         *
         * @param array $xs
         * @param array $expectedReciprocals
         *
         * @throws       \Exception
         */
        public function testReciprocal(array $xs, array $expectedReciprocals)
        {
            // When
            $reciprocals = Single::reciprocal($xs);

            // Then
            $this->assertEquals($expectedReciprocals, $reciprocals);
        }

        /**
         * @test   reciprocal when there are zeros
         * @throws Exception\BadDataException
         */
        public function testReciprocalWithZeros()
        {
            // Given
            $xs = [1, 2, 0, 3, 0];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            Single::reciprocal($xs);
        }
    }
