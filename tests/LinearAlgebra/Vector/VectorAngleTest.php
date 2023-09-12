<?php

    namespace MathPHP\Tests\LinearAlgebra\Vector;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\VectorException;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    use function rad2deg;
    use function sqrt;

    use const M_PI;

    class VectorAngleTest extends TestCase {
        /**
         * Test data created with online calculator: https://www.emathhelp.net/calculators/linear-algebra/angle-between-two-vectors-calculator
         *
         * @return array
         */
        public static function dataProviderForDegAngle(): array
        {
            return [
                [
                    [1, 2, 3],
                    [3, 2, 1],
                    rad2deg(num: acos(num: 5 / 7)),
                ],
                [
                    [1, 2, 3],
                    [3, 2, 1],
                    44.415308597193,
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    rad2deg(num: acos(num: 0)),
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    90,
                ],
                [
                    [1, 0, 0],
                    [1, 0, 0],
                    rad2deg(num: acos(num: 1)),
                ],
                [
                    [1, 0, 0],
                    [1, 0, 0],
                    0,
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    rad2deg(num: acos(num: 0)),
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    90,
                ],
                [
                    [-1, 1, 0],
                    [0, 1, -1],
                    rad2deg(num: acos(num: 1 / 2)),
                ],
                [
                    [-1, 1, 0],
                    [0, 1, -1],
                    60,
                ],
                [
                    [1, 0, 0],
                    [-1, 0, 0],
                    rad2deg(num: acos(num: -1)),
                ],
                [
                    [1, 0, 0],
                    [-1, 0, 0],
                    180,
                ],
                [
                    [23, 41, 33],
                    [31, 56, 21],
                    rad2deg(num: acos(num: 1851 * sqrt(num: 2 / 7485431))),
                ],
                [
                    [23, 41, 33],
                    [31, 56, 21],
                    16.9062176097913,
                ],
            ];
        }

        /**
         * Test data created with online calculator: https://www.emathhelp.net/calculators/linear-algebra/angle-between-two-vectors-calculator
         *
         * @return array
         */
        public static function dataProviderForRadAngle(): array
        {
            return [
                [
                    [1, 2, 3],
                    [3, 2, 1],
                    acos(num: 5 / 7),
                ],
                [
                    [1, 2, 3],
                    [3, 2, 1],
                    0.775193373310361,
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    acos(num: 0),
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    1.5707963267949,
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    acos(num: 0),
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    1.5707963267949,
                ],
                [
                    [1, 0, 0],
                    [1, 0, 0],
                    acos(num: 1),
                ],
                [
                    [1, 0, 0],
                    [1, 0, 0],
                    0,
                ],
                [
                    [-1, 1, 0],
                    [0, 1, -1],
                    acos(num: 1 / 2),
                ],
                [
                    [-1, 1, 0],
                    [0, 1, -1],
                    1.0471975511966,
                ],
                [
                    [1, 0, 0],
                    [-1, 0, 0],
                    acos(num: -1),
                ],
                [
                    [1, 0, 0],
                    [-1, 0, 0],
                    M_PI,
                ],
                [
                    [23, 41, 33],
                    [31, 56, 21],
                    acos(num: 1851 * sqrt(num: 2 / 7485431)),
                ],
                [
                    [23, 41, 33],
                    [31, 56, 21],
                    0.295069161349504,
                ],
            ];
        }

        public static function dataProviderForExceptionRadAngle(): array
        {
            return [
                [
                    [1, 2, 3],
                    [0, 0, 0],
                ],
                [
                    [0, 0, 0],
                    [3, 2, 1],
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ];
        }

        /**
         * @test         Angle between two vectors in degrees
         * @dataProvider dataProviderForDegAngle
         *
         * @param array $A
         * @param array $B
         * @param float $expected
         */
        public function testAngleBetweenDegrees(
            array $A,
            array $B,
            float $expected
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $angle1 = $A->angleBetween($B, TRUE);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }
            try
            {
                $angle2 = $B->angleBetween($A, TRUE);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $angle1, 00000000001);
            $this->assertEqualsWithDelta($expected, $angle2, 00000000001);
        }

        /**
         * @test         Angle between two vectors in radians
         * @dataProvider dataProviderForRadAngle
         *
         * @param array $A
         * @param array $B
         * @param float $expected
         */
        public function testAngleBetweenRadians(
            array $A,
            array $B,
            float $expected
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $angle1 = $A->angleBetween($B);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }
            try
            {
                $angle2 = $B->angleBetween($A);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected, $angle1, 00000000001);
            $this->assertEqualsWithDelta($expected, $angle2, 00000000001);
        }

        /**
         * @test         angle between vectors exception for null vector
         * @dataProvider dataProviderForExceptionRadAngle
         *
         * @param array $A
         * @param array $B
         */
        public function testExceptionRadAngle(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->expectException(BadDataException::class);

            // When
            try
            {
                $A->angleBetween($B);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }
        }
    }
