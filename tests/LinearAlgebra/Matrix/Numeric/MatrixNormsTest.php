<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Numeric;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use PHPUnit\Framework\TestCase;

    class MatrixNormsTest extends TestCase {
        public static function dataProviderForOneNorm(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    12,
                ],
                [
                    [
                        [1, 8, 3],
                        [2, 8, 4],
                        [3, 8, 5],
                    ],
                    24,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    25,
                ],
                [
                    [
                        [-20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    25,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, 55],
                    ],
                    67,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, -55],
                    ],
                    67,
                ],
                [
                    [
                        [1],
                        [2],
                        [3],
                    ],
                    6,
                ],
            ];
        }

        public static function dataProviderForInfinityNorm(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    12,
                ],
                [
                    [
                        [1, 8, 3],
                        [2, 8, 4],
                        [3, 8, 5],
                    ],
                    16,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    25,
                ],
                [
                    [
                        [-20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    25,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, 55],
                    ],
                    57,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, -55],
                    ],
                    57,
                ],
                [
                    [
                        [1],
                        [2],
                        [3],
                    ],
                    3,
                ],
                [
                    [
                        [1, 2, 3],
                    ],
                    6,
                ],
            ];
        }

        public static function dataProviderForMaxNorm(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    5,
                ],
                [
                    [
                        [1, 8, 3],
                        [2, 8, 4],
                        [3, 8, 5],
                    ],
                    8,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    20,
                ],
                [
                    [
                        [-20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    20,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, 55],
                    ],
                    55,
                ],
                [
                    [
                        [20, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [0, 2, -55],
                    ],
                    55,
                ],
                [
                    [
                        [1],
                        [2],
                        [3],
                    ],
                    3,
                ],
                [
                    [
                        [1, 2, 3],
                    ],
                    3,
                ],
            ];
        }

        public static function dataProviderForFrobeniusNorm(): array
        {
            return [
                [
                    [
                        [1, -7],
                        [2, 3],
                    ],
                    7.93725,
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    9.643651,
                ],
                [
                    [
                        [1, 5, 3, 9],
                        [2, 3, 4, 12],
                        [4, 2, 5, 11],
                    ],
                    21.330729,
                ],
                [
                    [
                        [1, 5, 3],
                        [2, 3, 4],
                        [4, 2, 5],
                        [6, 6, 3],
                    ],
                    13.784049,
                ],
                [
                    [
                        [5, -4, 2],
                        [-1, 2, 3],
                        [-2, 1, 0],
                    ],
                    8,
                ],
            ];
        }

        /**
         * @test         oneNorm
         * @dataProvider dataProviderForOneNorm
         */
        public function testOneNorm(array $A, $expected)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // When
            $norm = $A->oneNorm();

            // Then
            $this->assertEqualsWithDelta($expected, $norm, 0.0001);
        }

        /**
         * @test         infinity norm
         * @dataProvider dataProviderForInfinityNorm
         */
        public function testInfinityNorm(array $A, $expected)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // When
            $norm = $A->infinityNorm();

            // Then
            $this->assertEqualsWithDelta($expected, $norm, 0.0001);
        }

        /**
         * @test         maxNorm
         * @dataProvider dataProviderForMaxNorm
         */
        public function testMaxNorm(array $A, $expected)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // When
            $norm = $A->maxNorm();

            // Then
            $this->assertEqualsWithDelta($expected, $norm, 0.0001);
        }

        /**
         * @test         frobeniusNorm
         * @dataProvider dataProviderForFrobeniusNorm
         */
        public function testFrobeniusNorm(array $A, $expected)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // When
            $norm = $A->frobeniusNorm();

            // Then
            $this->assertEqualsWithDelta($expected, $norm, 0.0001);
        }
    }
