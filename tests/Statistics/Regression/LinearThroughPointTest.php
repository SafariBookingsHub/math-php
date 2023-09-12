<?php

    namespace MathPHP\Tests\Statistics\Regression;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Statistics\Regression\LinearThroughPoint;
    use MathPHP\Statistics\Regression\Regression;
    use PHPUnit\Framework\TestCase;

    class LinearThroughPointTest extends TestCase {
        /**
         * @return array [points]
         */
        public static function dataProviderForEquation(): array
        {
            return [
                [[[0, 0], [1, 1], [2, 2], [3, 3], [4, 4]]],
                [[[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]]],
                [
                    [
                        [4, 390],
                        [9, 580],
                        [10, 650],
                        [14, 730],
                        [4, 410],
                        [7, 530],
                        [12, 600],
                        [22, 790],
                        [1, 350],
                        [3, 400],
                        [8, 590],
                        [11, 640],
                        [5, 450],
                        [6, 520],
                        [10, 690],
                        [11, 690],
                        [16, 770],
                        [13, 700],
                        [13, 730],
                        [10, 640],
                    ],
                ],
            ];
        }

        /**
         * @return array [points, force_point, m, b]
         */
        public static function dataProviderForParameters(): array
        {
            return [
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    [0, 0],
                    1.35365853658537,
                    0,
                ],
                [
                    [
                        [4, 390],
                        [9, 580],
                        [10, 650],
                        [14, 730],
                        [4, 410],
                        [7, 530],
                        [12, 600],
                        [22, 790],
                        [1, 350],
                        [3, 400],
                        [8, 590],
                        [11, 640],
                        [5, 450],
                        [6, 520],
                        [10, 690],
                        [11, 690],
                        [16, 770],
                        [13, 700],
                        [13, 730],
                        [10, 640],
                    ],
                    [0, 0],
                    54.9003101462118,
                    0,
                ],
                [
                    [[11, 15], [17, 23], [23, 31], [29, 39]],
                    [0, 0],
                    1.348314,
                    0,
                ],
                [
                    [
                        [100, 140],
                        [200, 230],
                        [300, 310],
                        [400, 400],
                        [500, 480],
                    ],
                    [300, 310],
                    0.85,
                    55,
                ],
            ];
        }

        /**
         * @return array [points, n]
         */
        public static function dataProviderForSampleSize(): array
        {
            return [
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    5,
                ],
                [
                    [
                        [4, 390],
                        [9, 580],
                        [10, 650],
                        [14, 730],
                        [4, 410],
                        [7, 530],
                        [12, 600],
                        [22, 790],
                        [1, 350],
                        [3, 400],
                        [8, 590],
                        [11, 640],
                        [5, 450],
                        [6, 520],
                        [10, 690],
                        [11, 690],
                        [16, 770],
                        [13, 700],
                        [13, 730],
                        [10, 640],
                    ],
                    20,
                ],
            ];
        }

        /**
         * @return array [points, x, y]
         */
        public static function dataProviderForEvaluate(): array
        {
            return [
                [
                    [[0, 0], [1, 1], [2, 2], [3, 3], [4, 4]], // y = x + 0
                    5,
                    5,
                ],
                [
                    [[0, 0], [1, 1], [2, 2], [3, 3], [4, 4]], // y = x + 0
                    18,
                    18,
                ],
                [
                    [[0, 0], [1, 2], [2, 4], [3, 6]], // y = 2x + 0
                    4,
                    8,
                ],
                [
                    [[0, 1], [1, 3.5], [2, 6]], // y = 2.5x + 1
                    5,
                    15.5,
                ],
                [
                    [[0, 2], [1, 1], [2, 0], [3, -1]], // y = -x + 2
                    4,
                    -0.571428571,
                ],
            ];
        }

        /**
         * @return array [points, x, p, ci]
         */
        public static function dataProviderForCI(): array
        {
            return [
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    2,
                    .05,
                    0.2644479205,
                ],
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    3,
                    .05,
                    0.3966718808,
                ],
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    3,
                    .1,
                    0.3045778477,
                ],
            ];
        }

        /**
         * @return array [points, x, p, q, pi]
         */
        public static function dataProviderForPI(): array
        {
            return [
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    2,
                    .05,
                    1,
                    1.226194563,
                ],
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    3,
                    .05,
                    1,
                    1.261336191,
                ],
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    // when q gets large, pi approaches ci.
                    3,
                    .1,
                    10000000,
                    0.3045779864,
                ],
            ];
        }

        /**
         * @return array [points, force, sums]
         */
        public static function dataProviderForSumSquares(): array
        {
            return [
                [
                    [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]],
                    [0, 0],
                    [
                        'sse' => 0.743902439,
                        'ssr' => 150.2560976,
                        'sst' => 151,
                    ],
                ],
                [
                    [[2, 3], [3, 4], [5, 6], [6, 8], [7, 9]],
                    [1, 1],
                    [
                        'sse' => 0.743902439,
                        'ssr' => 150.2560976,
                        'sst' => 151,
                    ],
                ],
            ];
        }

        /**
         * @test constructor
         */
        public function testConstructor()
        {
            // Given
            $points = [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]];
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertInstanceOf(Regression::class,
                $regression);
            $this->assertInstanceOf(LinearThroughPoint::class,
                $regression);
        }

        /**
         * @test getPoints
         */
        public function testGetPoints()
        {
            // Given
            $points = [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]];
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEquals($points, $regression->getPoints());
        }

        /**
         * @test getXs
         */
        public function testGetXs()
        {
            // Given
            $points = [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]];
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEquals([1, 2, 4, 5, 6], $regression->getXs());
        }

        /**
         * @test getYs
         */
        public function testGetYs()
        {
            // Given
            $points = [[1, 2], [2, 3], [4, 5], [5, 7], [6, 8]];
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEquals([2, 3, 5, 7, 8], $regression->getYs());
        }

        /**
         * @test         getEquation - Equation matches pattern y = mx + b
         * @dataProvider dataProviderForEquation
         *
         * @param array $points
         */
        public function testGetEquation(array $points)
        {
            // Given
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertRegExp('/^y = [-]?\d+[.]\d+x [+\-] \d+[.]\d+$/',
                $regression->getEquation());
        }

        /**
         * @test         getParameters
         * @dataProvider dataProviderForParameters
         *
         * @param array $points
         * @param array $force_point
         * @param float $m
         * @param float $b
         */
        public function testGetParameters(
            array $points,
            array $force_point,
            float $m,
            float $b
        ) {
            // Given
            $regression = new LinearThroughPoint($points, $force_point);

            // When
            $parameters = $regression->getParameters();

            // Then
            $this->assertEqualsWithDelta($m, $parameters['m'], 0.0001);
            $this->assertEqualsWithDelta($b, $parameters['b'], 0.0001);
        }

        /**
         * @test         getSampleSize
         * @dataProvider dataProviderForSampleSize
         *
         * @param array $points
         * @param int   $n
         */
        public function testGetSampleSize(array $points, int $n)
        {
            // Given
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEquals($n, $regression->getSampleSize());
        }

        /**
         * @test         evaluate
         * @dataProvider dataProviderForEvaluate
         *
         * @param array $points
         * @param float $x
         * @param float $y
         */
        public function testEvaluate(array $points, float $x, float $y)
        {
            // Given
            $force = [0, 0];

            // When
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEqualsWithDelta($y, $regression->evaluate($x), 0.0001);
        }

        /**
         * @test         ci
         * @dataProvider dataProviderForCI
         *
         * @param array $points
         * @param float $x
         * @param float $p
         * @param float $ci
         *
         * @throws       \Exception
         */
        public function testCI(array $points, float $x, float $p, float $ci)
        {
            // Given
            $regression = new LinearThroughPoint($points);

            // Then
            $this->assertEqualsWithDelta($ci, LinearThroughPoint::ci($x, $p),
                .0000001);
        }

        /**
         * @test         PI
         * @dataProvider dataProviderForPI
         *
         * @param array $points
         * @param float $x
         * @param float $p
         * @param float $q
         * @param float $pi
         *
         * @throws       \Exception
         */
        public function testPI(
            array $points,
            float $x,
            float $p,
            float $q,
            float $pi
        ) {
            // Given
            $regression = new LinearThroughPoint($points);

            // Then
            $this->assertEqualsWithDelta($pi,
                LinearThroughPoint::pi($x, $p, $q),
                .0000001);
        }

        /**
         * @test         sum of squares
         * @dataProvider dataProviderForSumSquares
         *
         * @param array $points
         * @param array $force
         * @param array $sums
         */
        public function testSumSquares(array $points, array $force, array $sums)
        {
            // Given
            $regression = new LinearThroughPoint($points, $force);

            // Then
            $this->assertEqualsWithDelta($sums['sse'],
                $regression->sumOfSquaresResidual(), .0000001);
            try
            {
                $this->assertEqualsWithDelta($sums['ssr'],
                    $regression->sumOfSquaresRegression(), .0000001);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $this->assertEqualsWithDelta($sums['sst'],
                    $regression->sumOfSquaresTotal(), .0000001);
            } catch (BadDataException $e)
            {
            }
        }
    }
