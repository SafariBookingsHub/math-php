<?php

    namespace MathPHP\Tests\Probability\Distribution\Discrete;

    use MathPHP\Probability\Distribution\Discrete\Bernoulli;
    use MathPHP\Probability\Distribution\Discrete\Binomial;
    use PHPUnit\Framework\TestCase;

    class BernoulliTest extends TestCase {
        public static function dataProviderForPMF(): array
        {
            return [
                [0, 0.6, 0.4],
                [1, 0.6, 0.6],
                [0, 0.3, 0.7],
                [1, 0.3, 0.3],
            ];
        }

        public static function dataProviderForCDF(): array
        {
            return [
                [0, 0.6, 0.4],
                [1, 0.6, 1],
                [0, 0.3, 0.7],
                [1, 0.3, 1],
                [-1, 0.5, 0],
            ];
        }

        /**
         * @return array [p, mean]
         */
        public static function dataProviderForMean(): array
        {
            return [
                [0.00001, 0.00001, 0, 0],
                [0.1, 0.1, 0, 0],
                [0.2, 0.2, 0, 0],
                [0.3, 0.3, 0, 0],
                [0.4, 0.4, 0, 0],
                [0.5, 0.5, 0.5, 0],
                [0.6, 0.6, 1, 1],
                [0.7, 0.7, 1, 1],
                [0.8, 0.8, 1, 1],
                [0.9, 0.9, 1, 1],
                [0.9999, 0.9999, 1, 1],
            ];
        }

        /**
         * @return array [p, median]
         */
        public static function dataProviderForMedian(): array
        {
            return [
                [0.00001, 0],
                [0.1, 0],
                [0.2, 0],
                [0.3, 0],
                [0.4, 0],
                [0.5, 0.5],
                [0.6, 1],
                [0.7, 1],
                [0.8, 1],
                [0.9, 1],
                [0.9999, 1],
            ];
        }

        /**
         * @return array [p, mode]
         */
        public static function dataProviderForMode(): array
        {
            return [
                [0.00001, [0]],
                [0.1, [0]],
                [0.2, [0]],
                [0.3, [0]],
                [0.4, [0]],
                [0.5, [0, 1]],
                [0.6, [1]],
                [0.7, [1]],
                [0.8, [1]],
                [0.9, [1]],
                [0.9999, [1]],
            ];
        }

        /**
         * @return array [p, variance]
         */
        public static function dataProviderForVariance(): array
        {
            return [
                [0.1, 0.09],
                [0.3, 0.21],
                [0.5, 0.25],
                [0.7, 0.21],
                [0.9, 0.09],
            ];
        }

        /**
         * @test         pmf
         * @dataProvider dataProviderForPMF
         *
         * @param int   $k
         * @param float $p
         * @param float $expectedPmf
         */
        public function testPmf(int $k, float $p, float $expectedPmf)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $pmf = $bernoulli->pmf($k);

            // Then
            $this->assertEqualsWithDelta($expectedPmf, $pmf, 0.000001);
        }

        /**
         * @test         pmf is same as Binomial with n = 1
         * @dataProvider dataProviderForMean
         *
         * @param float $p
         */
        public function testPmfIsBinomialWithNEqualsOne(float $p)
        {
            // Given
            $bernoulli = new Bernoulli($p);
            $binomial = new Binomial(1, $p);

            // Then
            $this->assertEquals($binomial->pmf(0), $bernoulli->pmf(0));
            $this->assertEquals($binomial->pmf(1), $bernoulli->pmf(1));
        }

        /**
         * @test         cdf
         * @dataProvider dataProviderForCDF
         *
         * @param int   $k
         * @param float $p
         * @param float $expectedCdf
         */
        public function testCdf(int $k, float $p, float $expectedCdf)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $cdf = $bernoulli->cdf($k);

            // Then
            $this->assertEqualsWithDelta($expectedCdf, $cdf, 0.000001);
        }

        /**
         * @test         mean
         * @dataProvider dataProviderForMean
         *
         * @param float $p
         * @param float $μ
         */
        public function testMean(float $p, float $μ)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $mean = $bernoulli->mean();

            // Then
            $this->assertEquals($μ, $mean);
        }

        /**
         * @test         median
         * @dataProvider dataProviderForMedian
         *
         * @param float $p
         * @param float $expected
         */
        public function testMedian(float $p, float $expected)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $median = $bernoulli->median();

            // Then
            $this->assertEquals($expected, $median);
        }

        /**
         * @test         mode
         * @dataProvider dataProviderForMode
         *
         * @param float   $p
         * @param float[] $expected
         */
        public function testMode(float $p, array $expected)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $mode = $bernoulli->mode();

            // Then
            $this->assertEquals($expected, $mode);
        }

        /**
         * @test         variance
         * @dataProvider dataProviderForVariance
         *
         * @param float $p
         * @param float $σ²
         */
        public function testVariance(float $p, float $σ²)
        {
            // Given
            $bernoulli = new Bernoulli($p);

            // When
            $variance = $bernoulli->variance();

            // Then
            $this->assertEqualsWithDelta($σ², $variance, 0.00001);
        }
    }
