<?php

    namespace MathPHP\Tests\Expression;

    use MathPHP\Exception;
    use MathPHP\Expression\Piecewise;
    use MathPHP\Expression\Polynomial;
    use PHPUnit\Framework\TestCase;
    use ReflectionMethod;

    class PiecewiseTest extends TestCase {
        /** @var Piecewise|Mock */
        private $piecewise;

        public static function dataProviderForEval(): array
        {
            return [
                // Test evaluation given a single interval, function
                [
                    [
                        [-100, 100],  // f interval: [-100, 100]
                    ],
                    [
                        [1, 0],       // new Polynomial([1, 0])  // f(x) = x
                    ],
                    [
                        -100, // p(-100) = f(-100) = -100
                        0,    // p(0) = f(0) = 0
                        1,    // p(1) = f(1) = 1
                        25,   // p(25) = f(25) = 25
                        100,  // p(100) = f(100) = 100
                    ],
                    [
                        -100, // p(-100) = f(-100) = -100
                        0,    // p(0) = f(0) = 0
                        1,    // p(1) = f(1) = 1
                        25,   // p(25) = f(25) = 25
                        100,  // p(100) = f(100) = 100
                    ],
                ],
                // Test evaluation in 3 intervals, functions
                [
                    [
                        [-100, -2, FALSE, TRUE], // f interval: [-100, -2)
                        [-2, 2],                 // g interval: [-2, 2]
                        [2, 100, TRUE, FALSE],    // h interval: (2, 100]
                    ],
                    [
                        [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                        [2],     // new Polynomial([2]),          // g(x) = 2
                        [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                    ],
                    [
                        -27,   // p(-27) = f(-27) = -(-27) = 27
                        -3,    // p(-3) = f(-3) = -(-3) = 3
                        -2,    // p(-2) = g(-2) = 2
                        -1,    // p(-1) = g(-1) = 2
                        0,     // p(0) = g(0) = 2
                        1,     // p(1) = g(1) = 2
                        2,     // p(2) = g(2) = 2
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                    [
                        27,    // p(-27) = f(-27) = -(-27) = 27
                        3,     // p(-3) = f(-3) = -(-3) = 3
                        2,     // p(-2) = g(-2) = 2
                        2,     // p(-1) = g(-1) = 2
                        2,     // p(0) = g(0) = 2
                        2,     // p(1) = g(1) = 2
                        2,     // p(2) = g(2) = 2
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                ],
                // Test evaluation of 3 intervals, and at discountinuous, intermediate point
                [
                    [
                        [-100, -2, FALSE, TRUE], // f interval: [-100, -2)
                        [-2, 2],                 // g interval: [-2, 2]
                        [2, 100, TRUE, FALSE],    // h interval: (2, 100]
                    ],
                    [
                        [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                        [100],   // new Polynomial([2]),          // g(x) = 100
                        [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                    ],
                    [
                        -27,   // p(-27) = f(-27) = -(-27) = 27
                        -3,    // p(-3) = f(-3) = -(-3) = 3
                        -2,    // p(-2) = g(-2) = 100
                        -1,    // p(-1) = g(-1) = 100
                        0,     // p(0) = g(0) = 100
                        1,     // p(1) = g(1) = 100
                        2,     // p(2) = g(2) = 100
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                    [
                        27,    // p(-27) = f(-27) = -(-27) = 27
                        3,     // p(-3) = f(-3) = -(-3) = 3
                        100,   // p(-2) = g(-2) = 2
                        100,   // p(-1) = g(-1) = 2
                        100,   // p(0) = g(0) = 2
                        100,   // p(1) = g(1) = 2
                        100,   // p(2) = g(2) = 100
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                ],
                // Test evaluation when intervals are given out of order
                [
                    [
                        [-2, 2],                 // g interval: [-2, 2]
                        [-100, -2, FALSE, TRUE], // f interval: [-100, -2)
                        [2, 100, TRUE, FALSE],    // h interval: (2, 100]
                    ],
                    [
                        [2],     // new Polynomial([2]),          // g(x) = 2
                        [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                        [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                    ],
                    [
                        -27,   // p(-27) = f(-27) = -(-27) = 27
                        -3,    // p(-3) = f(-3) = -(-3) = 3
                        -2,    // p(-2) = g(-2) = 2
                        -1,    // p(-1) = g(-1) = 2
                        0,     // p(0) = g(0) = 2
                        1,     // p(1) = g(1) = 2
                        2,     // p(2) = g(2) = 2
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                    [
                        27,    // p(-27) = f(-27) = -(-27) = 27
                        3,     // p(-3) = f(-3) = -(-3) = 3
                        2,     // p(-2) = g(-2) = 2
                        2,     // p(-1) = g(-1) = 2
                        2,     // p(0) = g(0) = 2
                        2,     // p(1) = g(1) = 2
                        2,     // p(2) = g(2) = 2
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                ],
                // Test evaluation at "jump" located at a single point
                [
                    [
                        [-100, -2],
                        // f interval: [-100, -2]
                        [-2, 2, TRUE, TRUE],
                        // g interval: (-2, 2)
                        [2, 2],
                        // z interval: [2, 2]    jump point
                        [2, 100, TRUE, FALSE],
                        // h interval: (2, 100]
                    ],
                    [
                        [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                        [2],     // new Polynomial([2]),          // g(x) = 2
                        [0],     // new Polynomial([0]),          // z(x) = 0
                        [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                    ],
                    [
                        -27,   // p(-27) = f(-27) = -(-27) = 27
                        -3,    // p(-3) = f(-3) = -(-3) = 3
                        -2,    // p(-2) = g(-2) = 2
                        -1,    // p(-1) = g(-1) = 2
                        0,     // p(0) = g(0) = 2
                        1,     // p(1) = g(1) = 2
                        2,     // p(2) = z(2) = 0  // jump point
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                    [
                        27,    // p(-27) = f(-27) = -(-27) = 27
                        3,     // p(-3) = f(-3) = -(-3) = 3
                        2,     // p(-2) = g(-2) = 2
                        2,     // p(-1) = g(-1) = 2
                        2,     // p(0) = g(0) = 2
                        2,     // p(1) = g(1) = 2
                        0,     // p(2) = z(2) = 0  // jump point
                        3,     // p(3) = h(3) = 3
                        20,    // p(20) = h(20) = 20
                        100,   // p(100) = h(100) = 100
                    ],
                ],
                // Large intervals
                [
                    [
                        [1499173200, 1499176800, FALSE, TRUE],
                        // f interval: [1499173200, 1499176800)
                        [1499176800, 1499180400],
                        // g interval: [1499176800, 1499180400]
                        [1499180400, 1499184000, TRUE, FALSE],
                        // h interval: (1499180400, 1499184000]
                    ],
                    [
                        [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                        [2],     // new Polynomial([2]),          // g(x) = 2
                        [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                    ],
                    [
                        1499173200,
                        // p(1499173200) = f(1499173200) = -(1499173200) = -1499173200
                        1499173201,
                        // p(1499173201) = f(1499173201) = -(1499173201) = -1499173201
                        1499176799,
                        // p(1499176799) = f(1499176799) = -(1499176799) = -1499176799
                        1499176800,
                        // p(1499176800) = g(1499176800) = 2
                        1499176801,
                        // p(1499176801) = g(1499176801) = 2
                        1499180400,
                        // p(1499180400) = g(1499180400) = 2
                        1499180401,
                        // p(1499180401) = h(1499180401) = 1499180401
                        1499184000,
                        // p(1499184000) = h(1499184000) = 1499184000
                    ],
                    [
                        -1499173200,
                        // p(1499173200) = f(1499173200) = -(1499173200) = -1499173200
                        -1499173201,
                        // p(1499173201) = f(1499173201) = -(1499173201) = -1499173201
                        -1499176799,
                        // p(1499176799) = f(1499176799) = -(1499176799) = -1499176799
                        2,
                        // p(1499176800) = g(1499176800) = 2
                        2,
                        // p(1499176801) = g(1499176801) = 2
                        2,
                        // p(1499180400) = g(1499180400) = 2
                        1499180401,
                        // p(1499180401) = h(1499180401) = 1499180401
                        1499184000,
                        // p(1499184000) = h(1499184000) = 1499184000
                    ],
                ],
            ];
        }

        public static function dataProviderForOpenOpen(): array
        {
            return [
                [TRUE, TRUE, TRUE],
                [TRUE, FALSE, FALSE],
                [FALSE, TRUE, FALSE],
                [FALSE, FALSE, FALSE],
            ];
        }

        public static function dataProviderForOpenClosed(): array
        {
            return [
                [TRUE, TRUE, FALSE],
                [TRUE, FALSE, TRUE],
                [FALSE, TRUE, FALSE],
                [FALSE, FALSE, FALSE],
            ];
        }

        public static function dataProviderForClosedOpen(): array
        {
            return [
                [TRUE, TRUE, FALSE],
                [TRUE, FALSE, FALSE],
                [FALSE, TRUE, TRUE],
                [FALSE, FALSE, FALSE],
            ];
        }

        public static function dataProviderForClosedClosed(): array
        {
            return [
                [TRUE, TRUE, FALSE],
                [TRUE, FALSE, FALSE],
                [FALSE, TRUE, FALSE],
                [FALSE, FALSE, TRUE],
            ];
        }

        /**
         * Set up mock Piecewise
         */
        public function setUp(): void
        {
            $this->piecewise = $this->getMockBuilder(Piecewise::class)
                ->disableOriginalConstructor()
                ->getMock();
        }

        /**
         * @test         Piecewise __invoke evaluates the expected function to get the expected result
         * @dataProvider dataProviderForEval
         *
         * @param array $intervals
         * @param array $polynomial_args
         * @param array $inputs
         * @param array $expected
         */
        public function testEval(
            array $intervals,
            array $polynomial_args,
            array $inputs,
            array $expected
        ) {
            // Precondition
            if (count($inputs) !== count($expected))
                $this->fail('Number of inputs and expected outputs must match');

            // Given
            $array_map = array_map(function ($args) {
                return new Polynomial($args);
            }, $polynomial_args);
            $functions = $array_map;
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }

            $n = count($inputs);
            for ($i = 0; $i < $n; $i++)
            {
                // When
                try
                {
                    $evaluated = $piecewise($inputs[$i]);
                } catch (Exception\BadDataException $e)
                {
                }

                // Then
                $this->assertEquals($expected[$i], $evaluated);
            }
        }

        public function testSubintervalsShareClosedPointException()
        {
            // Given
            $intervals = [
                [-100, -2],                    // f interval: [-100, -2]
                [-2, 2],                       // g interval: [-2, 2]
                [2, 100],                       // h interval: [2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testSubintervalsOverlapException()
        {
            // Given
            $intervals = [
                [-100, -2],                    // f interval: [-100, -2]
                [-5, 1],                       // g interval: [-2, 1]
                [2, 100],                       // h interval: [2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testSubintervalDecreasingException()
        {
            // Given
            $intervals = [
                [-100, -2],                    // f interval: [-100, -2]
                [2, -2, TRUE, TRUE],           // g interval: (-2, 2)
                [2, 100],                       // h interval: [2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testSubintervalContainsMoreThanTwoPoints()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],      // f interval: [-100, -2)
                [0, 2, 3],                    // g interval: [0, 3]
                [3, 100, TRUE, FALSE],         // h interval: (3, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testSubintervalContainsOnePoints()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],      // f interval: [-100, -2)
                [-2],                         // g interval: [-2, -2]
                [3, 100, TRUE, FALSE],         // h interval: (3, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testSubintervalContainsOpenPoint()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],      // f interval: [-100, -2)
                [-2, -2, TRUE, TRUE],         // g interval: (-2, 2)
                [3, 100, TRUE, FALSE],         // h interval: (3, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testInputFunctionsAreNotCallableException()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],          // f interval: [-100, -2)
                [-2, 2],                          // g interval: [-2, 2]
                [2, 100, TRUE, FALSE],             // h interval: (2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                2,                            // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testNumberOfIntervalsAndFunctionsUnequalException()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],      // f interval: [-100, -2)
                [0, 2],                       // g interval: [0, 2]
                [2, 100, TRUE, FALSE],         // h interval: (2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testEvaluationNotInDomainException()
        {
            // Given
            $intervals = [
                [-100, -2, FALSE, TRUE],      // f interval: [-100, -2)
                [0, 2],                       // g interval: [0, 2]
                [2, 100, TRUE, FALSE],         // h interval: (2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $evaluation = $piecewise(-1);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testEvaluatedAtOpenPointException()
        {
            // Given
            $intervals = [
                [-100, -2, TRUE, TRUE],      // f interval: (-100, -2)
                [-2, 2, TRUE, TRUE],         // g interval: (0, 2)
                [2, 100, TRUE, TRUE],         // h interval: (2, 100)
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $evaluation = $piecewise(2);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        public function testDuplicatedIntervalException()
        {
            // Given
            $intervals = [
                [-100, -2, TRUE, TRUE],      // f interval: (-100, -2)
                [-100, -2, TRUE, TRUE],      // g interval: [-100, -2)
                [2, 100],        // h interval: [2, 100]
            ];
            $functions = [
                new Polynomial([-1, 0]),      // f(x) = -x
                new Polynomial([2]),          // g(x) = 2
                new Polynomial([1, 0]),        // h(x) = x
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $piecewise = new Piecewise($intervals, $functions);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test     preconditionExceptions throws an Exception\BadDataException if intervals and functions do not have the same number of elements
         */
        public function testConstructorPreconditionCountException()
        {
            // Given
            $intervals = [
                [1, 2],
                [2, 3],
            ];
            $functions = [
                new Polynomial([2]),
            ];

            // And
            $preconditions = new ReflectionMethod(Piecewise::class,
                'constructorPreconditions');
            $preconditions->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $preconditions->invokeArgs($this->piecewise,
                    [$intervals, $functions]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test     preconditionExceptions throws an Exception\BadDataException if the functions are not callable
         */
        public function testConstructorPreconditionCallableException()
        {
            // Given
            $intervals = [
                [1, 2],
                [2, 3],
            ];
            $functions = [
                'not a function',
                'certainly not callable',
            ];

            // And
            $preconditions = new ReflectionMethod(Piecewise::class,
                'constructorPreconditions');
            $preconditions->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $preconditions->invokeArgs($this->piecewise,
                    [$intervals, $functions]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test     checkAsAndBs throws an Exception\BadDataException if a point is not closed
         */
        public function testCheckAsAndBsExceptionPointNotClosed()
        {
            // Given
            [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen] = [
                1,
                1,
                NULL,
                NULL,
                NULL,
                TRUE,
                TRUE,
            ];

            // And
            $checkAsAndBs = new ReflectionMethod(Piecewise::class,
                'checkAsAndBs');
            $checkAsAndBs->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $checkAsAndBs->invokeArgs($this->piecewise,
                    [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test     checkAsAndBs throws an Exception\BadDataException if interval not increasing
         */
        public function testCheckAsAndBsExceptionIntervalNotIncreasing()
        {
            // Given
            [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen] = [
                2,
                1,
                NULL,
                NULL,
                NULL,
                TRUE,
                TRUE,
            ];

            // And
            $checkAsAndBs = new ReflectionMethod(Piecewise::class,
                'checkAsAndBs');
            $checkAsAndBs->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $checkAsAndBs->invokeArgs($this->piecewise,
                    [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test     checkAsAndBs throws an Exception\BadDataException if two intervals share a point that is closed at both ends
         */
        public function testCheckAsAndBsExceptionTwoIntervalsSharePointNotClosedAtBothEnds(
        )
        {
            // Given
            [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen] = [
                1,
                2,
                NULL,
                1,
                FALSE,
                FALSE,
                TRUE,
            ];

            // And
            $checkAsAndBs = new ReflectionMethod(Piecewise::class,
                'checkAsAndBs');
            $checkAsAndBs->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $checkAsAndBs->invokeArgs($this->piecewise,
                    [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test     checkAsAndBs throws an Exception\BadDataException if one interval starts or ends inside another interval
         */
        public function testCheckAsAndBsExceptionOverlappingIntervals()
        {
            // Given
            [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen] = [
                3,
                4,
                2,
                4,
                TRUE,
                TRUE,
                TRUE,
            ];

            // And
            $checkAsAndBs = new ReflectionMethod(Piecewise::class,
                'checkAsAndBs');
            $checkAsAndBs->setAccessible(TRUE);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $checkAsAndBs->invokeArgs($this->piecewise,
                    [$a, $b, $lastA, $lastB, $lastBOpen, $aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }
        }

        /**
         * @test         openOpen interval
         * @dataProvider dataProviderForOpenOpen
         *
         * @param bool $aOpen
         * @param bool $bOpen
         * @param bool $expected
         */
        public function testOpenOpen(bool $aOpen, bool $bOpen, bool $expected)
        {
            // Given
            $openOpen = new ReflectionMethod(Piecewise::class, 'openOpen');
            $openOpen->setAccessible(TRUE);

            // When
            try
            {
                $result = $openOpen->invokeArgs($this->piecewise,
                    [$aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }

            // Then
            $this->assertSame($expected, $result);
        }

        /**
         * @test         openClosed interval
         * @dataProvider dataProviderForOpenClosed
         *
         * @param bool $aOpen
         * @param bool $bOpen
         * @param bool $expected
         */
        public function testOpenClosed(bool $aOpen, bool $bOpen, bool $expected)
        {
            // Given
            $openOpen = new ReflectionMethod(Piecewise::class, 'openClosed');
            $openOpen->setAccessible(TRUE);

            // When
            try
            {
                $result = $openOpen->invokeArgs($this->piecewise,
                    [$aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }

            // Then
            $this->assertSame($expected, $result);
        }

        /**
         * @test         closedOpen interval
         * @dataProvider dataProviderForClosedOpen
         *
         * @param bool $aOpen
         * @param bool $bOpen
         * @param bool $expected
         */
        public function testClosedOpen(bool $aOpen, bool $bOpen, bool $expected)
        {
            // Given
            $openOpen = new ReflectionMethod(Piecewise::class, 'closedOpen');
            $openOpen->setAccessible(TRUE);

            try
            {
                $result = $openOpen->invokeArgs($this->piecewise,
                    [$aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }

            // Then
            $this->assertSame($expected, $result);
        }

        /**
         * @test         closedClosed interval
         * @dataProvider dataProviderForClosedClosed
         *
         * @param bool $aOpen
         * @param bool $bOpen
         * @param bool $expected
         */
        public function testClosedClosed(
            bool $aOpen,
            bool $bOpen,
            bool $expected
        ) {
            // Given
            $openOpen = new ReflectionMethod(Piecewise::class, 'closedClosed');
            $openOpen->setAccessible(TRUE);

            // When
            try
            {
                $result = $openOpen->invokeArgs($this->piecewise,
                    [$aOpen, $bOpen]);
            } catch (\ReflectionException $e)
            {
            }

            // Then
            $this->assertSame($expected, $result);
        }
    }
