<?php

    namespace MathPHP\Tests\Statistics\Regression;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception;
    use MathPHP\Statistics\Regression\Linear;
    use PHPUnit\Framework\TestCase;

    class LeastSquaresTest extends TestCase {
        /**
         * @return array [points]
         */
        #[ArrayShape([
            'zero_points' => "array[]",
            'one_point'   => "array[]",
            'two_points'  => "array[]",
        ])] public static function dataProviderForLeastSquaresDegreesOfFreedomBadDataException(
        ): array
        {
            return [
                'zero_points' => [
                    [],
                ],
                'one_point'   => [
                    [[1, 2]],
                ],
                'two_points'  => [
                    [[1, 2], [2, 3]],
                ],
            ];
        }

        /**
         * @test         LeastSquares trait leastSquares method throws a BadDataException if degrees of freedom is ≤ 0
         *               That will happen if there are only one or two points being used to fit a regression line.
         * @dataProvider dataProviderForLeastSquaresDegreesOfFreedomBadDataException
         *
         * @param array $points
         */
        public function testLeastSquaresDegreesOfFreedomBadDataException(
            array $points
        ) {
            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            $regression = new Linear($points);
        }
    }
