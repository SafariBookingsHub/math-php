<?php

    namespace MathPHP\Tests\Probability\Distribution\Continuous;

    use Exception;
    use MathPHP\Probability\Distribution\Continuous\DiracDelta;
    use PHPUnit\Framework\TestCase;

    use function range;

    use const INF;

    class DiracDeltaTest extends TestCase {
        /**
         * @return array [x, pdf]
         */
        public static function dataProviderForPdf(): array
        {
            return [
                [-100, 0],
                [-12, 0],
                [-2, 0],
                [-1, 0],
                [-0.5, 0],
                [0, INF],
                [0.5, 0],
                [1, 0],
                [2, 0],
                [12, 0],
                [100, 0],
            ];
        }

        /**
         * @return array [x, cdf]
         */
        public static function dataProviderForCdf(): array
        {
            return [
                [-100, 0],
                [-12, 0],
                [-2, 0],
                [-1, 0],
                [-0.5, 0],
                [0, 1],
                [0.5, 1],
                [1, 1],
                [2, 1],
                [12, 1],
                [100, 1],
            ];
        }

        /**
         * @test         pdf
         * @dataProvider dataProviderForPdf
         *
         * @param float $x
         * @param float $expectedPdf
         */
        public function testPdf(float $x, float $expectedPdf)
        {
            // Given
            $dirac = new DiracDelta();

            // When
            $pdf = $dirac->pdf($x);

            // Then
            $this->assertEquals($expectedPdf, $pdf);
        }

        /**
         * @testCase     cdf
         * @dataProvider dataProviderForCdf
         *
         * @param float $x
         * @param int   $expectedCdf
         */
        public function testCdf(float $x, int $expectedCdf)
        {
            // Given
            $dirac = new DiracDelta();

            // When
            $cdf = $dirac->cdf($x);

            // Then
            $this->assertSame($expectedCdf, $cdf);
        }

        /**
         * @testCase inverse is always 0
         */
        public function testInverse()
        {
            // Given
            $diracDelta = new DiracDelta();

            foreach (range(-10, 10, 0.5) as $p)
            {
                // When
                $inverse = $diracDelta->inverse($p);

                // Then
                $this->assertEquals(0, $inverse);
            }
        }

        /**
         * @testCase rand is always 0
         */
        public function testRand()
        {
            // Given
            $diracDelta = new DiracDelta();

            foreach (range(-10, 10, 0.5) as $ignored)
            {
                // When
                try
                {
                    $rand = $diracDelta->rand();
                } catch (Exception $e)
                {
                }

                // Then
                $this->assertEquals(0, $rand);
            }
        }

        /**
         * @testCase mean is always 0
         */
        public function testMean()
        {
            // Given
            $diracDelta = new DiracDelta();

            foreach (range(-10, 10, 0.5) as $ignored)
            {
                // When
                $mean = $diracDelta->mean();

                // Then
                $this->assertEquals(0, $mean);
            }
        }

        /**
         * @testCase median is always 0
         */
        public function testMedian()
        {
            // Given
            $diracDelta = new DiracDelta();

            foreach (range(-10, 10, 0.5) as $ignored)
            {
                // When
                $median = $diracDelta->median();

                // Then
                $this->assertEquals(0, $median);
            }
        }

        /**
         * @testCase mode is always 0
         */
        public function testMode()
        {
            // Given
            $diracDelta = new DiracDelta();

            foreach (range(-10, 10, 0.5) as $ignored)
            {
                // When
                $mode = DiracDelta::mode();

                // Then
                $this->assertEquals(0, $mode);
            }
        }
    }
