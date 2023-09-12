<?php

    namespace MathPHP\Tests\LinearAlgebra\Vector;

    use MathPHP\Exception\BadDataException;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    class VectorDistanceTest extends TestCase {
        public static function dataProviderForDifferentVectors(): array
        {
            return [
                [[1, 2, 3], [0, 0]],
                [[0, 0, 0], [3, 2]],
                [[0, 0], [0, 0, 0]],
                [[3, 4], [4, 3, 2]],
                [[1, 1], [1, 1, 1]],
            ];
        }

        public static function dataProviderForL1Distance(): array
        {
            return [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    2,
                ],
                [
                    [1, 1, 0],
                    [0, 1, 0],
                    1,
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                    6,
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    0,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    0,
                ],
                [
                    [56, 26, 83],
                    [11, 82, 95],
                    113,
                ],
            ];
        }

        public static function dataProviderForL2Distance(): array
        {
            return [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    1.4142135623730951,
                ],
                [
                    [1, 1, 0],
                    [0, 1, 0],
                    1,
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                    3.7416573867739413,
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    0,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    0,
                ],
                [
                    [56, 26, 83],
                    [11, 82, 95],
                    72.83543093852057,
                ],
            ];
        }

        public static function dataProviderForMinkowskiDistance(): array
        {
            return [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    1,
                    2,
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    2,
                    1.4142135623730951,
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    3,
                    1.2599210498948732,
                ],
                [
                    [1, 1, 0],
                    [0, 1, 0],
                    1,
                    1,
                ],
                [
                    [1, 1, 0],
                    [0, 1, 0],
                    2,
                    1,
                ],
                [
                    [1, 1, 0],
                    [0, 1, 0],
                    3,
                    1,
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                    1,
                    6,
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                    2,
                    3.7416573867739413,
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                    3,
                    3.3019272488946263,
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    1,
                    0,
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    2,
                    0,
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    3,
                    0,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    1,
                    0,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    2,
                    0,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    3,
                    0,
                ],
                [
                    [56, 26, 83],
                    [11, 82, 95],
                    1,
                    113,
                ],
                [
                    [56, 26, 83],
                    [11, 82, 95],
                    2,
                    72.83543093852057,
                ],
                [
                    [56, 26, 83],
                    [11, 82, 95],
                    3,
                    64.51064463863402,
                ],
            ];
        }

        /**
         * @test         minkowskiDistance exception when vectors don't have the same size
         * @dataProvider dataProviderForDifferentVectors
         *
         * @param array $A
         * @param array $B
         */
        public function testMinkowskiDistanceExceptionDifferentVectors(
            array $A,
            array $B
        ) {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->expectException(BadDataException::class);

            //When
            try
            {
                $A->minkowskiDistance(B: $B, p: 2);
            } catch (BadDataException $e)
            {
            }
        }

        /**
         * @test         l1Distance
         * @dataProvider dataProviderForL1Distance
         *
         * @param array $A
         * @param array $B
         * @param float $expected
         */
        public function testL1Distance(array $A, array $B, float $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $distance1 = $A->l1Distance(B: $B);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $distance2 = $B->l1Distance(B: $A);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $distance1, 0.0000000001);
            $this->assertEqualsWithDelta($expected, $distance2, 0.0000000001);
        }

        /**
         * @test         euclidean distance
         * @dataProvider dataProviderForL2Distance
         *
         * @param array $A
         * @param array $B
         * @param float $expected
         */
        public function testL2Distance(array $A, array $B, float $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $distance1 = $A->l2Distance(B: $B);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $distance2 = $B->l2Distance(B: $A);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $distance1, 0.0000000001);
            $this->assertEqualsWithDelta($expected, $distance2, 0.0000000001);
        }

        /**
         * @test         minkowski distance
         * @dataProvider dataProviderForMinkowskiDistance
         *
         * @param array $A
         * @param array $B
         * @param int   $p
         * @param float $expected
         */
        public function testMinkowskiDistance(
            array $A,
            array $B,
            int $p,
            float $expected
        ) {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $distance1 = $A->minkowskiDistance(B: $B, p: $p);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $distance2 = $B->minkowskiDistance(B: $A, p: $p);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $distance1, 0.0000000001);
            $this->assertEqualsWithDelta($expected, $distance2, 0.0000000001);
        }
    }
