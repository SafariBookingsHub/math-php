<?php

    namespace MathPHP\Tests;

    use MathPHP\Trigonometry;
    use PHPUnit\Framework\TestCase;

    use const M_SQRT1_2;

    class TrigonometryTest extends TestCase {
        /**
         * @return array
         */
        public static function dataProviderForUnitCircle(): array
        {
            return [
                [5, [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]]],
                [
                    9,
                    [
                        [1, 0],
                        [M_SQRT1_2, M_SQRT1_2],
                        [0, 1],
                        [-M_SQRT1_2, M_SQRT1_2],
                        [-1, 0],
                        [-M_SQRT1_2, -M_SQRT1_2],
                        [0, -1],
                        [M_SQRT1_2, -M_SQRT1_2],
                        [1, 0],
                    ],
                ],
            ];
        }

        /**
         * @test         unitCircle returns points on a unit circle.
         * @dataProvider dataProviderForUnitCircle
         *
         * @param int   $points
         * @param array $expected
         */
        public function testUnitCircle(int $points, array $expected)
        {
            // When
            $unitCircle = Trigonometry::unitCircle($points);

            // Then
            $this->assertEqualsWithDelta($expected, $unitCircle, 0.00000001);
        }
    }
