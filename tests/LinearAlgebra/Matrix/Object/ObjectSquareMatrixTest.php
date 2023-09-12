<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Object;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception;
    use MathPHP\Expression\Polynomial;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\ObjectSquareMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use MathPHP\Number\Complex;
    use MathPHP\Number\ObjectArithmetic;
    use PHPUnit\Framework\TestCase;
    use stdClass;

    class ObjectSquareMatrixTest extends TestCase {
        #[ArrayShape(shape: [
            'rows have different types'    => "array",
            'columns have different types' => "array",
            'not square'                   => "array",
        ])] public static function dataProviderConstructorException(): array
        {
            return [
                'rows have different types'    => [
                    [[new stdClass()]],
                    Exception\IncorrectTypeException::class,
                ],
                'columns have different types' => [
                    [
                        [
                            new stdClass(),
                            new Polynomial(coefficients: [1, 2, 3]),
                        ],
                        [
                            new stdClass(),
                            new Polynomial(coefficients: [1, 2, 3]),
                        ],
                    ],
                    Exception\IncorrectTypeException::class,
                ],
                'not square'                   => [
                    [
                        [
                            new Polynomial(coefficients: [1, 2]),
                            new Polynomial(coefficients: [2, 1]),
                        ],
                    ],
                    Exception\MatrixException::class,
                ],
            ];
        }

        public static function dataProviderForArithmeticExceptions(): array
        {
            try
            {
                return [
                    [ // Different Sizes
                      [
                          [
                              new Polynomial(coefficients: [1, 2, 3]),
                              new Polynomial(coefficients: [1, 2, 3]),
                          ],
                          [
                              new Polynomial(coefficients: [1, 2, 3]),
                              new Polynomial(coefficients: [1, 2, 3]),
                          ],
                      ],
                      MatrixFactory::create(A: [
                          [
                              new Polynomial(coefficients: [
                                  1,
                                  2,
                                  3,
                              ]),
                          ],
                      ]),
                      Exception\MatrixException::class,
                    ],
                    [ // Different Types
                      [[new Polynomial(coefficients: [1, 2, 3])]],
                      new ObjectSquareMatrix(A: [[new Complex(r: 1, i: 2)]]),
                      Exception\IncorrectTypeException::class,
                    ],
                    [ // Not a Matrix
                      [[new Polynomial(coefficients: [1, 2, 3])]],
                      new Complex(r: 1, i: 2),
                      Exception\IncorrectTypeException::class,
                    ],
                ];
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        #[ArrayShape(shape: [
            'same'               => "array",
            'different types'    => "\array[][]",
            'different contents' => "array",
            'different shapes'   => "array",
        ])] public static function dataProviderisEqual(): array
        {
            return [
                'same'               => [
                    [[new Polynomial(coefficients: [1, 0])]],
                    [[new Polynomial(coefficients: [1, 0])]],
                    TRUE,
                ],
                'different types'    => [
                    [[new Polynomial(coefficients: [1, 0])]],
                    [[1]],
                    FALSE,
                ],
                'different contents' => [
                    [[new Polynomial(coefficients: [1, 0])]],
                    [[new Polynomial(coefficients: [1, 1])]],
                    FALSE,
                ],
                'different shapes'   => [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [new Polynomial(coefficients: [1, 0])],
                        [new Polynomial(coefficients: [1, 0])],
                    ],
                    FALSE,
                ],
            ];
        }

        public static function dataProviderAdd(): array
        {
            return [
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [0, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [0, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [2, 0]),
                            new Polynomial(coefficients: [1, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1]),
                            new Polynomial(coefficients: [2, 0]),
                        ],
                    ],
                ],
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [2, 0]),
                            new Polynomial(coefficients: [2, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [2, 1]),
                            new Polynomial(coefficients: [2, 0]),
                        ],
                    ],
                ],
            ];
        }

        public static function dataProviderSubtract(): array
        {
            return [
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [0, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [0, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [2, 1]),
                            new Polynomial(coefficients: [2, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, -1]),
                            new Polynomial(coefficients: [-1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [-1, -1]),
                            new Polynomial(coefficients: [-2, -1]),
                        ],
                        [
                            new Polynomial(coefficients: [-1, 1]),
                            new Polynomial(coefficients: [2, 0]),
                        ],
                    ],
                ],
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [-2, 0]),
                            new Polynomial(coefficients: [1, -1]),
                        ],
                        [
                            new Polynomial(coefficients: [-2, 2]),
                            new Polynomial(coefficients: [4, 4]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [3, 0]),
                            new Polynomial(coefficients: [0, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [3, -2]),
                            new Polynomial(coefficients: [-3, -4]),
                        ],
                    ],
                ],
            ];
        }

        public static function dataProviderMul(): array
        {
            return [
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [0, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [0, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [1, 0, 0]),
                            new Polynomial(coefficients: [1, 1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1, 0]),
                            new Polynomial(coefficients: [1, 0, 0]),
                        ],
                    ],
                ],
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 1]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 1]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        [
                            new Polynomial(coefficients: [2, 1, 0]),
                            new Polynomial(coefficients: [2, 1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [2, 1, 0]),
                            new Polynomial(coefficients: [2, 1, 0]),
                        ],
                    ],
                ],
            ];
        }

        public static function dataProviderMultiplyVector(): array
        {
            return [
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [0, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [0, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                    ],
                    [
                        new Polynomial(coefficients: [1, 0]),
                        new Polynomial(coefficients: [1, 1]),
                    ],
                    [
                        [new Polynomial(coefficients: [1, 0, 0])],
                        [new Polynomial(coefficients: [1, 1, 0])],
                    ],
                ],
            ];
        }

        public static function dataProviderDet(): array
        {
            return [
                [
                    [
                        [new Polynomial(coefficients: [1, 0])],
                    ],
                    new Polynomial(coefficients: [1, 0]),
                ],
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [1, 0]),
                        ],
                        [
                            new Polynomial(coefficients: [1, 0]),
                            new Polynomial(coefficients: [0, 4]),
                        ],
                    ],
                    new Polynomial(coefficients: [-1, 4, 0]),
                ],
            ];
        }

        /**
         * @test         The constructor throws the proper exceptions
         * @dataProvider dataProviderConstructorException
         *
         * @param array  $A
         * @param string $exception
         */
        public function testMatrixConstructorException(
            array $A,
            string $exception
        ) {
            // Then
            $this->expectException($exception);

            // When
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         Addition throws the proper exceptions
         * @dataProvider dataProviderForArithmeticExceptions
         *
         * @param array            $A
         * @param ObjectArithmetic $B
         * @param string           $exception
         */
        public function testMatrixAddException(
            array $A,
            ObjectArithmetic $B,
            string $exception
        ) {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                $C = $A->add($B);
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         Subtraction throws the proper exceptions
         * @dataProvider dataProviderForArithmeticExceptions
         *
         * @param array            $A
         * @param ObjectArithmetic $B
         * @param string           $exception
         */
        public function testMatrixSubtractException(
            array $A,
            ObjectArithmetic $B,
            string $exception
        ) {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                $C = $A->subtract($B);
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         Multiplication throws the proper exceptions
         * @dataProvider dataProviderForArithmeticExceptions
         *
         * @param array            $A
         * @param ObjectArithmetic $B
         * @param string           $exception
         *
         * @throws \MathPHP\Exception\MathException
         */
        public function testMatrixMultiplyException(
            array $A,
            ObjectArithmetic $B,
            string $exception
        ) {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                try
                {
                    $C = $A->multiply(object_or_scalar: $B);
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test         isEqual
         * @dataProvider dataProviderisEqual
         *
         * @param array $A
         * @param array $B
         * @param bool  $expected
         *
         * @throws       \Exception
         */
        public function testIsEqual(array $A, array $B, bool $expected)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $comparison = $A->isEqual($B);

            // Then
            $this->assertEquals($expected, $comparison);
        }

        /**
         * @test         add
         * @dataProvider dataProviderAdd
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testAdd(array $A, array $B, array $expected)
        {
            // Given
            $A = new ObjectSquareMatrix(A: $A);
            $B = new ObjectSquareMatrix(A: $B);

            // And
            $expected = matrixFactory::create(A: $expected);

            // When
            $sum = $A->add($B);

            // Then
            $this->assertEquals($expected, $sum);
        }

        /**
         * @test         subtract
         * @dataProvider dataProviderSubtract
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testSubtract(array $A, array $B, array $expected)
        {
            // Given
            $A = new ObjectSquareMatrix(A: $A);
            $B = new ObjectSquareMatrix(A: $B);
            $expected = new ObjectSquareMatrix(A: $expected);

            // When
            $difference = $A->subtract($B);

            // Then
            $this->assertEquals($expected, $difference);
        }

        /**
         * @test         multiply
         * @dataProvider dataProviderMul
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         *
         * @throws \MathPHP\Exception\MathException
         */
        public function testMul(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new ObjectSquareMatrix(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // And
            try
            {
                $expected = matrixFactory::create(A: $expected);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $sum = $A->multiply(object_or_scalar: $B);
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($expected, $sum);
        }

        /**
         * @test         Matrix can be multiplied by a vector
         * @dataProvider dataProviderMultiplyVector
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         *
         * @throws \MathPHP\Exception\MathException
         */
        public function testMultiplyVector(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $sum = $A->multiply(object_or_scalar: $B);
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }

            // Then
            try
            {
                $expected = MatrixFactory::create(A: $expected);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            $this->assertEquals($expected, $sum);
        }

        /**
         * @test         det
         * @dataProvider dataProviderDet
         *
         * @param array      $A
         * @param Polynomial $expected
         *
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         */
        public function testDet(array $A, Polynomial $expected)
        {
            // Given
            try
            {
                $A = new ObjectSquareMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $det = $A->det();
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                }
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($det, $expected);

            // And when
            try
            {
                try
                {
                    $det = $A->det();
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                }
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($expected, $det);
        }
    }
