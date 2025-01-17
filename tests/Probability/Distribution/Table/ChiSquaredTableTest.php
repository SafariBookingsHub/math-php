<?php

    namespace MathPHP\Tests\Probability\Distribution\Table;

    use MathPHP\Exception;
    use MathPHP\Probability\Distribution\Table\ChiSquared;
    use PHPUnit\Framework\TestCase;

    use function sprintf;

    class ChiSquaredTableTest extends TestCase {
        public static function dataProviderForTable(): array
        {
            return [
                [1, 0.995, 0.0000393],
                [1, 0.05, 3.841],
                [1, 0.050, 3.841],
                [1, 0.01, 6.635],
                [5, 0.05, 11.070],
                [5, 0.01, 15.086],
            ];
        }

        /**
         * @test         values from constant
         * @dataProvider dataProviderForTable
         *
         * @param int   $df
         * @param float $p
         * @param float $χ²
         */
        public function testChiSquaredValuesFromConstant(
            int $df,
            float $p,
            float $χ²
        ) {
            // Given
            $p = sprintf('%1.3f', $p);

            // When
            $value = ChiSquared::CHI_SQUARED_SCORES[$df][$p];

            // Then
            $this->assertEquals($χ², $value);
        }

        /**
         * @test         values from function
         * @dataProvider dataProviderForTable
         */
        public function testChiSquaredValuesFromFunction(
            int $df,
            float $p,
            float $χ²
        ) {
            // When
            try
            {
                $value = ChiSquared::getChiSquareValue($df, $p);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertEquals($χ², $value);
        }

        /**
         * @test   exception
         * @throws Exception\BadDataException
         */
        public function testChiSquaredException()
        {
            // Given
            $df = 88474;
            $p = 0.44;

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            ChiSquared::getChiSquareValue($df, $p);
        }
    }
