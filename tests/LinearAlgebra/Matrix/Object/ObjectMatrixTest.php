<?php
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Object;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception;
    use MathPHP\Expression\Polynomial;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\ObjectMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use MathPHP\Number\ArbitraryInteger;
    use MathPHP\Number\Complex;
    use MathPHP\Number\ObjectArithmetic;
    use PHPUnit\Framework\TestCase;
    use stdClass;

    class ObjectMatrixTest extends TestCase {
        #[ArrayShape(shape: [
            'object does not implement ObjectArithmetic'         => "array",
            'multiple objects do not implement ObjectArithmetic' => "array",
            'objects are not the same type'                      => "array",
            'different row counts'                               => "array",
        ])] public static function dataProviderConstructorException(): array
        {
            try
            {
                return [
                    'object does not implement ObjectArithmetic'         => [
                        [[new stdClass()]],
                        Exception\IncorrectTypeException::class,
                    ],
                    'multiple objects do not implement ObjectArithmetic' => [
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
                    'objects are not the same type'                      => [
                        [
                            [
                                new ArbitraryInteger(number: 5),
                                new Polynomial(coefficients: [1, 2, 3]),
                            ],
                            [
                                new ArbitraryInteger(number: 5),
                                new Polynomial(coefficients: [1, 2, 3]),
                            ],
                        ],
                        Exception\IncorrectTypeException::class,
                    ],
                    'different row counts'                               => [
                        [
                            [
                                new Polynomial(coefficients: [1, 2, 3]),
                                new Polynomial(coefficients: [1, 2, 3]),
                            ],
                            [new Polynomial(coefficients: [1, 2, 3])],
                        ],
                        Exception\BadDataException::class,
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
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
                      new ObjectMatrix(A: [[new Complex(r: 1, i: 2)]]),
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

        public static function dataProviderDetException(): array
        {
            return [
                [
                    [
                        [
                            new Polynomial(coefficients: [1, 2]),
                            new Polynomial(coefficients: [2, 1]),
                        ],
                    ],
                ],
            ];
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

        public static function dataProviderForCofactor(): array
        {
            try
            {
                return [
                    [
                        [
                            [
                                new ArbitraryInteger(number: 1),
                                new ArbitraryInteger(number: 4),
                                new ArbitraryInteger(number: 7),
                            ],
                            [
                                new ArbitraryInteger(number: 3),
                                new ArbitraryInteger(number: 0),
                                new ArbitraryInteger(number: 5),
                            ],
                            [
                                new ArbitraryInteger(number: -1),
                                new ArbitraryInteger(number: 9),
                                new ArbitraryInteger(number: 11),
                            ],
                        ],
                        0,
                        0,
                        new ArbitraryInteger(number: -45),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
        }

        public static function dataProviderForTrace(): array
        {
            try
            {
                return [
                    [
                        [
                            [new ArbitraryInteger(number: 1)],
                        ],
                        new ArbitraryInteger(number: 1),
                    ],
                    [
                        [
                            [
                                new ArbitraryInteger(number: 1),
                                new ArbitraryInteger(number: 2),
                            ],
                            [
                                new ArbitraryInteger(number: 2),
                                new ArbitraryInteger(number: 3),
                            ],
                        ],
                        new ArbitraryInteger(number: 4),
                    ],
                    [
                        [
                            [
                                new ArbitraryInteger(number: 1),
                                new ArbitraryInteger(number: 2),
                                new ArbitraryInteger(number: 3),
                            ],
                            [
                                new ArbitraryInteger(number: 4),
                                new ArbitraryInteger(number: 5),
                                new ArbitraryInteger(number: 6),
                            ],
                            [
                                new ArbitraryInteger(number: 7),
                                new ArbitraryInteger(number: 8),
                                new ArbitraryInteger(number: 9),
                            ],
                        ],
                        new ArbitraryInteger(number: 15),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                $C = $A->add(object_or_scalar: $B);
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                $C = $A->subtract(object_or_scalar: $B);
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
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
         * @test         Cannot compute the determinant of a non-square matrix
         * @dataProvider dataProviderDetException
         *
         * @param array $A
         *
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         */
        public function testMatrixDetException(array $A)
        {
            // Given
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

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
            $A = new ObjectMatrix(A: $A);
            $B = new ObjectMatrix(A: $B);

            // And
            $expected = matrixFactory::create(A: $expected);

            // When
            $sum = $A->add(object_or_scalar: $B);

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
            $A = new ObjectMatrix(A: $A);
            $B = new ObjectMatrix(A: $B);
            $expected = new ObjectMatrix(A: $expected);

            // When
            $difference = $A->subtract(object_or_scalar: $B);

            // Then
            $this->assertEquals($expected->getMatrix(),
                $difference->getMatrix());
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new ObjectMatrix(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
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
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
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

        /**
         * @test         cofactor
         * @dataProvider dataProviderForCofactor
         *
         * @param array            $A
         * @param int              $mᵢ
         * @param int              $nⱼ
         * @param ArbitraryInteger $Cᵢⱼ
         */
        public function testCofactor(
            array $A,
            int $mᵢ,
            int $nⱼ,
            ArbitraryInteger $Cᵢⱼ
        ) {
            // Given
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            $cofactor = $A->cofactor(mᵢ: $mᵢ, nⱼ: $nⱼ);

            // Then
            $this->assertEquals($Cᵢⱼ, $cofactor);
            $this->assertEquals($Cᵢⱼ->toInt(), $cofactor->toInt());
        }

        /**
         * @test transpose
         */
        public function testTranspose()
        {
            // Given
            try
            {
                $A = [
                    [
                        new ArbitraryInteger(number: 1),
                        new ArbitraryInteger(number: 4),
                    ],
                    [
                        new ArbitraryInteger(number: 3),
                        new ArbitraryInteger(number: 0),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // And
            try
            {
                $expected = [
                    [
                        new ArbitraryInteger(number: 1),
                        new ArbitraryInteger(number: 3),
                    ],
                    [
                        new ArbitraryInteger(number: 4),
                        new ArbitraryInteger(number: 0),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            $Aᵀ = $A->transpose();

            // Then
            $this->assertEquals($expected, $Aᵀ->getMatrix());
        }

        /**
         * @test scalarMultiply
         */
        public function testScalarMultiply()
        {
            // Given
            try
            {
                $A = [
                    [
                        new ArbitraryInteger(number: 1),
                        new ArbitraryInteger(number: 4),
                    ],
                    [
                        new ArbitraryInteger(number: -3),
                        new ArbitraryInteger(number: 0),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // And
            $λ = 2;

            // When
            $λA = $A->scalarMultiply(λ: $λ);

            // Then
            try
            {
                $expected = new ObjectMatrix(A: [
                    [
                        new ArbitraryInteger(number: 2),
                        new ArbitraryInteger(number: 8),
                    ],
                    [
                        new ArbitraryInteger(number: -6),
                        new ArbitraryInteger(number: 0),
                    ],
                ]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException|Exception\BadParameterException $e)
            {
            }
            $this->assertEquals($expected->getMatrix(), $λA->getMatrix());
        }

        /**
         * @test scalarMultiply by an object
         */
        public function testScalarMultiplyByObject()
        {
            // Given
            try
            {
                $A = [
                    [
                        new ArbitraryInteger(number: 1),
                        new ArbitraryInteger(number: 4),
                    ],
                    [
                        new ArbitraryInteger(number: -3),
                        new ArbitraryInteger(number: 0),
                    ],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // And
            try
            {
                $λ = new ArbitraryInteger(number: 2);
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            $λA = $A->scalarMultiply(λ: $λ);

            // Then
            try
            {
                $expected = new ObjectMatrix(A: [
                    [
                        new ArbitraryInteger(number: 2),
                        new ArbitraryInteger(number: 8),
                    ],
                    [
                        new ArbitraryInteger(number: -6),
                        new ArbitraryInteger(number: 0),
                    ],
                ]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException|Exception\BadParameterException $e)
            {
            }
            $this->assertEquals($expected->getMatrix(), $λA->getMatrix());
        }

        /**
         * @test createZeroValue
         */
        public function testCreateZeroValue()
        {
            // Given
            $zeroMatrix = ObjectMatrix::createZeroValue();

            // And
            try
            {
                $expected = [
                    [new ArbitraryInteger(number: 0)],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected, $zeroMatrix->getMatrix());
        }

        /**
         * @test         trace
         * @dataProvider dataProviderForTrace
         *
         * @param array            $A
         * @param ObjectArithmetic $tr
         */
        public function testTrace(array $A, ObjectArithmetic $tr)
        {
            // Given
            try
            {
                $A = new ObjectMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $trace = $A->trace();
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($tr, $trace);
        }

        /**
         * @test trace error when matrix not square
         */
        public function testTraceNotSquare()
        {
            // Given
            try
            {
                $A = new ObjectMatrix(A: [
                    [
                        new ArbitraryInteger(number: 1),
                        new ArbitraryInteger(number: 2),
                    ],
                ]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException|Exception\BadParameterException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $tr = $A->trace();
            } catch (Exception\MatrixException $e)
            {
            }
        }
    }
