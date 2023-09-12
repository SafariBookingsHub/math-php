<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

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
        #[ArrayShape(['object does not implement ObjectArithmetic'         => "array",
                      'multiple objects do not implement ObjectArithmetic' => "array",
                      'objects are not the same type'                      => "array",
                      'different row counts'                               => "array"
        ])] public static function dataProviderConstructorException(): array
        {
            try
            {
                return [
                    'object does not implement ObjectArithmetic' => [
                        [[new stdClass()]],
                        Exception\IncorrectTypeException::class,
                    ],
                    'multiple objects do not implement ObjectArithmetic' => [
                        [
                            [new stdClass(), new Polynomial([1, 2, 3])],
                            [new stdClass(), new Polynomial([1, 2, 3])],
                        ],
                        Exception\IncorrectTypeException::class,
                    ],
                    'objects are not the same type' => [
                        [
                            [
                                new ArbitraryInteger(5),
                                new Polynomial([1, 2, 3]),
                            ],
                            [
                                new ArbitraryInteger(5),
                                new Polynomial([1, 2, 3]),
                            ],
                        ],
                        Exception\IncorrectTypeException::class,
                    ],
                    'different row counts' => [
                        [
                            [
                                new Polynomial([1, 2, 3]),
                                new Polynomial([1, 2, 3]),
                            ],
                            [new Polynomial([1, 2, 3])],
                        ],
                        Exception\BadDataException::class,
                    ],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
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
                              new Polynomial([1, 2, 3]),
                              new Polynomial([1, 2, 3]),
                          ],
                          [
                              new Polynomial([1, 2, 3]),
                              new Polynomial([1, 2, 3]),
                          ],
                      ],
                      MatrixFactory::create([[new Polynomial([1, 2, 3])]]),
                      Exception\MatrixException::class,
                    ],
                    [ // Different Types
                      [[new Polynomial([1, 2, 3])]],
                      new ObjectMatrix([[new Complex(1, 2)]]),
                      Exception\IncorrectTypeException::class,
                    ],
                    [ // Not a Matrix
                      [[new Polynomial([1, 2, 3])]],
                      new Complex(1, 2),
                      Exception\IncorrectTypeException::class,
                    ],
                ];
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
        }

        public static function dataProviderDetException(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 2]), new Polynomial([2, 1])],
                    ],
                ],
            ];
        }

        /**
         * @return array
         */
        #[ArrayShape(['same'               => "array",
                      'different types'    => "\array[][]",
                      'different contents' => "array",
                      'different shapes'   => "array"
        ])] public static function dataProviderisEqual(): array
        {
            return [
                'same'               => [
                    [[new Polynomial([1, 0])]],
                    [[new Polynomial([1, 0])]],
                    TRUE,
                ],
                'different types'    => [
                    [[new Polynomial([1, 0])]],
                    [[1]],
                    FALSE,
                ],
                'different contents' => [
                    [[new Polynomial([1, 0])]],
                    [[new Polynomial([1, 1])]],
                    FALSE,
                ],
                'different shapes'   => [
                    [[new Polynomial([1, 0]), new Polynomial([1, 0])]],
                    [[new Polynomial([1, 0])], [new Polynomial([1, 0])]],
                    FALSE,
                ],
            ];
        }

        public static function dataProviderAdd(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([0, 0])],
                        [new Polynomial([0, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 1])],
                        [new Polynomial([1, 1]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([2, 0]), new Polynomial([1, 1])],
                        [new Polynomial([1, 1]), new Polynomial([2, 0])],
                    ],
                ],
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 1])],
                        [new Polynomial([1, 1]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([2, 0]), new Polynomial([2, 1])],
                        [new Polynomial([2, 1]), new Polynomial([2, 0])],
                    ],
                ],
            ];
        }

        public static function dataProviderSubtract(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([0, 0])],
                        [new Polynomial([0, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([2, 1]), new Polynomial([2, 1])],
                        [new Polynomial([1, -1]), new Polynomial([-1, 0])],
                    ],
                    [
                        [new Polynomial([-1, -1]), new Polynomial([-2, -1])],
                        [new Polynomial([-1, 1]), new Polynomial([2, 0])],
                    ],
                ],
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([-2, 0]), new Polynomial([1, -1])],
                        [new Polynomial([-2, 2]), new Polynomial([4, 4])],
                    ],
                    [
                        [new Polynomial([3, 0]), new Polynomial([0, 1])],
                        [new Polynomial([3, -2]), new Polynomial([-3, -4])],
                    ],
                ],
            ];
        }

        public static function dataProviderMul(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([0, 0])],
                        [new Polynomial([0, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 1])],
                        [new Polynomial([1, 1]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([1, 0, 0]), new Polynomial([1, 1, 0])],
                        [new Polynomial([1, 1, 0]), new Polynomial([1, 0, 0])],
                    ],
                ],
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 1])],
                        [new Polynomial([1, 1]), new Polynomial([1, 0])],
                    ],
                    [
                        [new Polynomial([2, 1, 0]), new Polynomial([2, 1, 0])],
                        [new Polynomial([2, 1, 0]), new Polynomial([2, 1, 0])],
                    ],
                ],
            ];
        }

        public static function dataProviderMultiplyVector(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([0, 0])],
                        [new Polynomial([0, 0]), new Polynomial([1, 0])],
                    ],
                    [new Polynomial([1, 0]), new Polynomial([1, 1])],
                    [
                        [new Polynomial([1, 0, 0])],
                        [new Polynomial([1, 1, 0])],
                    ],
                ],
            ];
        }

        public static function dataProviderDet(): array
        {
            return [
                [
                    [
                        [new Polynomial([1, 0])],
                    ],
                    new Polynomial([1, 0]),
                ],
                [
                    [
                        [new Polynomial([1, 0]), new Polynomial([1, 0])],
                        [new Polynomial([1, 0]), new Polynomial([0, 4])],
                    ],
                    new Polynomial([-1, 4, 0]),
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
                                new ArbitraryInteger(1),
                                new ArbitraryInteger(4),
                                new ArbitraryInteger(7),
                            ],
                            [
                                new ArbitraryInteger(3),
                                new ArbitraryInteger(0),
                                new ArbitraryInteger(5),
                            ],
                            [
                                new ArbitraryInteger(-1),
                                new ArbitraryInteger(9),
                                new ArbitraryInteger(11),
                            ],
                        ],
                        0,
                        0,
                        new ArbitraryInteger(-45),
                    ],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
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
                            [new ArbitraryInteger(1)],
                        ],
                        new ArbitraryInteger(1),
                    ],
                    [
                        [
                            [new ArbitraryInteger(1), new ArbitraryInteger(2)],
                            [new ArbitraryInteger(2), new ArbitraryInteger(3)],
                        ],
                        new ArbitraryInteger(4),
                    ],
                    [
                        [
                            [
                                new ArbitraryInteger(1),
                                new ArbitraryInteger(2),
                                new ArbitraryInteger(3),
                            ],
                            [
                                new ArbitraryInteger(4),
                                new ArbitraryInteger(5),
                                new ArbitraryInteger(6),
                            ],
                            [
                                new ArbitraryInteger(7),
                                new ArbitraryInteger(8),
                                new ArbitraryInteger(9),
                            ],
                        ],
                        new ArbitraryInteger(15),
                    ],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
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
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
         */
        public function testMatrixMultiplyException(
            array $A,
            ObjectArithmetic $B,
            string $exception
        ) {
            // Given
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException($exception);

            // When
            try
            {
                $C = $A->multiply($B);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test         Cannot compute the determinant of a non-square matrix
         * @dataProvider dataProviderDetException
         *
         * @param array $A
         */
        public function testMatrixDetException(array $A)
        {
            // Given
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $det = $A->det();
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
            $A = MatrixFactory::create($A);
            $B = MatrixFactory::create($B);

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
            $A = new ObjectMatrix($A);
            $B = new ObjectMatrix($B);

            // And
            $expected = matrixFactory::create($expected);

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
            $A = new ObjectMatrix($A);
            $B = new ObjectMatrix($B);
            $expected = new ObjectMatrix($expected);

            // When
            $difference = $A->subtract($B);

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
         */
        public function testMul(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $B = new ObjectMatrix($B);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // And
            try
            {
                $expected = matrixFactory::create($expected);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // When
            try
            {
                $sum = $A->multiply($B);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
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
         */
        public function testMultiplyVector(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            try
            {
                $sum = $A->multiply($B);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            try
            {
                $expected = MatrixFactory::create($expected);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
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
         */
        public function testDet(array $A, Polynomial $expected)
        {
            // Given
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // When
            try
            {
                $det = $A->det();
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($det, $expected);

            // And when
            try
            {
                $det = $A->det();
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
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // When
            $cofactor = $A->cofactor($mᵢ, $nⱼ);

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
                    [new ArbitraryInteger(1), new ArbitraryInteger(4)],
                    [new ArbitraryInteger(3), new ArbitraryInteger(0)],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // And
            try
            {
                $expected = [
                    [new ArbitraryInteger(1), new ArbitraryInteger(3)],
                    [new ArbitraryInteger(4), new ArbitraryInteger(0)],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $Aᵀ = $A->transpose();
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }

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
                    [new ArbitraryInteger(1), new ArbitraryInteger(4)],
                    [new ArbitraryInteger(-3), new ArbitraryInteger(0)],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // And
            $λ = 2;

            // When
            try
            {
                $λA = $A->scalarMultiply($λ);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            try
            {
                $expected = new ObjectMatrix([
                    [new ArbitraryInteger(2), new ArbitraryInteger(8)],
                    [new ArbitraryInteger(-6), new ArbitraryInteger(0)],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
                    [new ArbitraryInteger(1), new ArbitraryInteger(4)],
                    [new ArbitraryInteger(-3), new ArbitraryInteger(0)],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // And
            try
            {
                $λ = new ArbitraryInteger(2);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $λA = $A->scalarMultiply($λ);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            try
            {
                $expected = new ObjectMatrix([
                    [new ArbitraryInteger(2), new ArbitraryInteger(8)],
                    [new ArbitraryInteger(-6), new ArbitraryInteger(0)],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
                    [new ArbitraryInteger(0)],
                ];
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
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
                $A = new ObjectMatrix($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
                $A = new ObjectMatrix([
                    [new ArbitraryInteger(1), new ArbitraryInteger(2)],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
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
