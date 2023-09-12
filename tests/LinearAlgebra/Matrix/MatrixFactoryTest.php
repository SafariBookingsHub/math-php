<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\ComplexMatrix;
    use MathPHP\LinearAlgebra\FunctionMatrix;
    use MathPHP\LinearAlgebra\Matrix;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericDiagonalMatrix;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\LinearAlgebra\NumericSquareMatrix;
    use MathPHP\LinearAlgebra\ObjectMatrix;
    use MathPHP\LinearAlgebra\ObjectSquareMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;
    use PHPUnit\Framework\TestCase;

    use function is_int;

    class MatrixFactoryTest extends TestCase {
        use MatrixDataProvider;

        public static function dataProviderForDiagonalMatrix(): array
        {
            return [
                [[1]],
                [[1, 2]],
                [[1, 2, 3]],
                [[1, 2, 3, 4]],
            ];
        }

        public static function dataProviderFromArrayOfVectors(): array
        {
            return [
                [
                    [
                        [1, 2],
                    ],
                    [
                        [1],
                        [2],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [1, 3],
                        [2, 4],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                        [5, 6],
                    ],
                    [
                        [1, 3, 5],
                        [2, 4, 6],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [3, 4, 5],
                        [5, 6, 6],
                    ],
                    [
                        [1, 3, 5],
                        [2, 4, 6],
                        [3, 5, 6],
                    ],
                ],
            ];
        }

        public static function dataProviderForFunctionSquareMatrix(): array
        {
            $function = fn($x) => $x * 2;

            return [
                [
                    [
                        [$function],
                    ],
                ],
                [
                    [
                        [$function, $function],
                        [$function, $function],
                    ],
                ],
                [
                    [
                        [$function, $function, $function],
                        [$function, $function, $function],
                        [$function, $function, $function],
                    ],
                ],
            ];
        }

        public static function dataProviderForFunctionMatrix(): array
        {
            $function = fn($x) => $x * 2;

            return [
                [
                    [
                        [$function, $function],
                    ],
                ],
                [
                    [
                        [$function, $function],
                        [$function, $function],
                        [$function, $function],
                    ],
                ],
                [
                    [
                        [$function, $function, $function],
                        [$function, $function, $function],
                        [$function, $function, $function],
                        [$function, $function, $function],
                    ],
                ],
            ];
        }

        public static function dataProviderForMatrix(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                        [4, 5, 6],
                    ],
                ],
            ];
        }

        public static function dataProviderForIdentity(): array
        {
            return [
                [
                    1,
                    [[1]],
                ],
                [
                    2,
                    [
                        [1, 0],
                        [0, 1],
                    ],
                ],
                [
                    3,
                    [
                        [1, 0, 0],
                        [0, 1, 0],
                        [0, 0, 1],
                    ],
                ],
                [
                    4,
                    [
                        [1, 0, 0, 0],
                        [0, 1, 0, 0],
                        [0, 0, 1, 0],
                        [0, 0, 0, 1],
                    ],
                ],
            ];
        }

        /**
         * @return array [n, R]
         */
        public static function dataProviderForDownshiftPermutation(): array
        {
            return [
                [
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    2,
                    [
                        [0, 1],
                        [1, 0],
                    ],
                ],
                [
                    3,
                    [
                        [0, 0, 1],
                        [1, 0, 0],
                        [0, 1, 0],
                    ],
                ],
                [
                    4,
                    [
                        [0, 0, 0, 1],
                        [1, 0, 0, 0],
                        [0, 1, 0, 0],
                        [0, 0, 1, 0],
                    ],
                ],
            ];
        }

        /**
         * @return array [n, R]
         */
        public static function dataProviderForUpshiftPermutation(): array
        {
            return [
                [
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    2,
                    [
                        [0, 1],
                        [1, 0],
                    ],
                ],
                [
                    3,
                    [
                        [0, 1, 0],
                        [0, 0, 1],
                        [1, 0, 0],
                    ],
                ],
                [
                    4,
                    [
                        [0, 1, 0, 0],
                        [0, 0, 1, 0],
                        [0, 0, 0, 1],
                        [1, 0, 0, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForExchange(): array
        {
            return [
                [
                    1,
                    [[1]],
                ],
                [
                    2,
                    [
                        [0, 1],
                        [1, 0],
                    ],
                ],
                [
                    3,
                    [
                        [0, 0, 1],
                        [0, 1, 0],
                        [1, 0, 0],
                    ],
                ],
                [
                    4,
                    [
                        [0, 0, 0, 1],
                        [0, 0, 1, 0],
                        [0, 1, 0, 0],
                        [1, 0, 0, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForZero(): array
        {
            return [
                [
                    1,
                    1,
                    [[0]],
                ],
                [
                    2,
                    2,
                    [
                        [0, 0],
                        [0, 0],
                    ],
                ],
                [
                    3,
                    3,
                    [
                        [0, 0, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
                [
                    2,
                    3,
                    [
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
                [
                    3,
                    2,
                    [
                        [0, 0],
                        [0, 0],
                        [0, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForOne(): array
        {
            return [
                [
                    1,
                    1,
                    [[1]],
                ],
                [
                    2,
                    2,
                    [
                        [1, 1],
                        [1, 1],
                    ],
                ],
                [
                    3,
                    3,
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                ],
                [
                    2,
                    3,
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                ],
                [
                    3,
                    2,
                    [
                        [1, 1],
                        [1, 1],
                        [1, 1],
                    ],
                ],
            ];
        }

        public static function dataProviderForEye(): array
        {
            return [
                [
                    1,
                    1,
                    0,
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    1,
                    1,
                    0,
                    9,
                    [
                        [9],
                    ],
                ],
                [
                    2,
                    2,
                    0,
                    1,
                    [
                        [1, 0],
                        [0, 1],
                    ],
                ],
                [
                    2,
                    2,
                    1,
                    1,
                    [
                        [0, 1],
                        [0, 0],
                    ],
                ],
                [
                    3,
                    3,
                    0,
                    1,
                    [
                        [1, 0, 0],
                        [0, 1, 0],
                        [0, 0, 1],
                    ],
                ],
                [
                    3,
                    3,
                    1,
                    1,
                    [
                        [0, 1, 0],
                        [0, 0, 1],
                        [0, 0, 0],
                    ],
                ],
                [
                    3,
                    3,
                    2,
                    1,
                    [
                        [0, 0, 1],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
                [
                    3,
                    3,
                    0,
                    9,
                    [
                        [9, 0, 0],
                        [0, 9, 0],
                        [0, 0, 9],
                    ],
                ],
                [
                    3,
                    3,
                    1,
                    9,
                    [
                        [0, 9, 0],
                        [0, 0, 9],
                        [0, 0, 0],
                    ],
                ],
                [
                    3,
                    3,
                    0,
                    -9,
                    [
                        [-9, 0, 0],
                        [0, -9, 0],
                        [0, 0, -9],
                    ],
                ],
                [
                    3,
                    4,
                    0,
                    1,
                    [
                        [1, 0, 0, 0],
                        [0, 1, 0, 0],
                        [0, 0, 1, 0],
                    ],
                ],
                [
                    3,
                    4,
                    1,
                    1,
                    [
                        [0, 1, 0, 0],
                        [0, 0, 1, 0],
                        [0, 0, 0, 1],
                    ],
                ],
                [
                    3,
                    4,
                    2,
                    1,
                    [
                        [0, 0, 1, 0],
                        [0, 0, 0, 1],
                        [0, 0, 0, 0],
                    ],
                ],
                [
                    3,
                    4,
                    3,
                    1,
                    [
                        [0, 0, 0, 1],
                        [0, 0, 0, 0],
                        [0, 0, 0, 0],
                    ],
                ],
                [
                    3,
                    4,
                    1,
                    9,
                    [
                        [0, 9, 0, 0],
                        [0, 0, 9, 0],
                        [0, 0, 0, 9],
                    ],
                ],
                [
                    4,
                    3,
                    0,
                    1,
                    [
                        [1, 0, 0],
                        [0, 1, 0],
                        [0, 0, 1],
                        [0, 0, 0],
                    ],
                ],
                [
                    4,
                    3,
                    1,
                    1,
                    [
                        [0, 1, 0],
                        [0, 0, 1],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
                [
                    4,
                    3,
                    2,
                    1,
                    [
                        [0, 0, 1],
                        [0, 0, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForEyeExceptions(): array
        {
            return [
                [-1, 2, 1, 1],
                [2, -1, 1, 1],
                [2, 2, -1, 1],
                [2, 2, 2, 1],
                [2, 2, 3, 1],
            ];
        }

        public static function dataProviderForCreateFromColumnVector(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [
                        [1],
                        [2],
                        [3],
                        [4],
                    ],
                ],
                [
                    [1],
                    [
                        [1],
                    ],
                ],
            ];
        }

        public static function dataProviderForConstructor(): array
        {
            return [
                [
                    [1, 2, 3, 4],
                    [[1, 2, 3, 4]],
                ],
                [
                    [1],
                    [[1]],
                ],
            ];
        }

        /**
         * @test         create numeric matrix
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForNotSquareMatrix
         * @dataProvider dataProviderForSingularMatrix
         * @dataProvider dataProviderForNonsingularMatrix
         * @dataProvider dataProviderForMatrixWithWeirdNumbers
         */
        public function testCreateNumericMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         createNumeric
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForNotSquareMatrix
         * @dataProvider dataProviderForSingularMatrix
         * @dataProvider dataProviderForNonsingularMatrix
         * @dataProvider dataProviderForMatrixWithWeirdNumbers
         */
        public function testSpecificallyCreateNumericMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::createNumeric(A: $A);
            } catch (Exception\BadDataException|Exception\MathException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         create diagonal matrix
         * @dataProvider dataProviderForDiagonalMatrix
         */
        public function testCreateDiagonalMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::diagonal(D: $A);
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericDiagonalMatrix::class, $A);
            $this->assertInstanceOf(NumericMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         create square matrix
         * @dataProvider dataProviderForSquareMatrix
         */
        public function testCreateSquareMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericSquareMatrix::class, $A);
            $this->assertInstanceOf(NumericMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         create from array of vectors
         * @dataProvider dataProviderFromArrayOfVectors
         */
        public function testCreateArrayOfVectors(
            array $vectors,
            array $expected
        ) {
            // Given
            $array_map1 = [];
            foreach ($vectors as $key => $vector)
            {
                $array_map1[$key] = new Vector($vector);
            }
            $array_map = $array_map1;
            $vectors = $array_map;

            // When
            try
            {
                try
                {
                    $A = MatrixFactory::createFromVectors(A: $vectors);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericMatrix::class, $A);
            $this->assertEquals($expected, $A->getMatrix());
        }

        /**
         * @test   create from array of vectors exception - different lengths
         * @throws \Exception
         */
        public function testCreateFromArrayOfVectorsExceptionVectorsDifferentLengths(
        )
        {
            // Given
            $A = [
                new Vector(A: [1, 2]),
                new Vector(A: [4, 5, 6]),
            ];

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A = MatrixFactory::createFromVectors(A: $A);
        }

        /**
         * @test         createFunctionMatrix
         * @dataProvider dataProviderForFunctionSquareMatrix
         */
        public function testCreateFunctionSquareMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::createFunctionMatrix(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertInstanceOf(FunctionMatrix::class,
                $A);
        }

        /**
         * @test         createFunctionMatrix
         * @dataProvider dataProviderForFunctionMatrix
         */
        public function testCreateFunctionMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::createFunctionMatrix(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertInstanceOf(FunctionMatrix::class,
                $A);
        }

        /**
         * @test createFunctionMatrix error when matrix not made of functions
         */
        public function testCreateFunctionMatrixErrorNotMadeOfFunctions()
        {
            // Given
            $A = [
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $A = MatrixFactory::createFunctionMatrix(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         create matrix
         * @dataProvider dataProviderForMatrix
         */
        public function testCreateMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericMatrix::class,
                $A);

            // And
            $this->assertNotInstanceOf(NumericSquareMatrix::class,
                $A);
            $this->assertNotInstanceOf(FunctionMatrix::class,
                $A);
            $this->assertNotInstanceOf(NumericDiagonalMatrix::class,
                $A);
        }

        /**
         * @test   check params exception for empty array
         * @throws \Exception
         */
        public function testCheckParamsExceptionEmptyArray()
        {
            // Given
            $A = [];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            $M = MatrixFactory::create(A: $A);
        }

        /**
         * @test   check params exception for single dimensional array
         * @throws \Exception
         */
        public function testCheckParamsExceptionSingleDimensionalArray()
        {
            // Given
            $A = [1, 2, 3];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            $M = MatrixFactory::create(A: $A);
        }

        /**
         * @test   matrix unknown type exception
         * @throws \Exception
         */
        public function testMatrixUnknownTypeException()
        {
            // Given
            $A = [
                [[1], [2], [3]],
                [[2], [3], [4]],
            ];

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            MatrixFactory::create(A: $A);
        }

        /**
         * @test         identity
         * @dataProvider dataProviderForIdentity
         *
         * @param int   $n
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testIdentity(int $n, array $R)
        {
            // Given
            $R = new NumericSquareMatrix(A: $R);

            // When
            $I = MatrixFactory::identity(n: $n);

            // Then
            $this->assertEquals($R, $I);
        }

        /**
         * @test         downshiftPermutation
         * @dataProvider dataProviderForDownshiftPermutation
         *
         * @param int   $n
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testDownshiftPermutation(int $n, array $R)
        {
            $R = new NumericSquareMatrix(A: $R);
            $this->assertEquals($R, MatrixFactory::downshiftPermutation(n: $n));
        }

        /**
         * @test         upshiftPermutation
         * @dataProvider dataProviderForUpshiftPermutation
         *
         * @param int   $n
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testUpshiftPermutation(int $n, array $R)
        {
            $R = new NumericSquareMatrix(A: $R);
            $this->assertEquals($R, MatrixFactory::upshiftPermutation(n: $n));
        }

        /**
         * @test   identity with n less than zero
         * @throws \Exception
         */
        public function testIdentityExceptionNLessThanZero()
        {
            // Given
            $n = -1;

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            MatrixFactory::identity(n: $n);
        }

        /**
         * @test         exchange
         * @dataProvider dataProviderForExchange
         *
         * @param int   $n
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testExchange(int $n, array $R)
        {
            // Given
            $R = new NumericSquareMatrix(A: $R);

            // When
            $E = MatrixFactory::exchange(n: $n);

            // Then
            $this->assertEquals($R, $E);
        }

        /**
         * @test   exchange exception - n less than zero
         * @throws \Exception
         */
        public function testExchangeExceptionNLessThanZero()
        {
            // When
            $n = -1;

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            MatrixFactory::exchange(n: $n);
        }

        /**
         * @test         zero
         * @dataProvider dataProviderForZero
         *
         * @param int   $m
         * @param int   $n
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testZero(int $m, int $n, array $R)
        {
            // Given
            $R = MatrixFactory::create(A: $R);

            // When
            $Z = MatrixFactory::zero(m: $m, n: $n);

            // Then
            $this->assertEquals($R, $Z);
        }

        /**
         * @test   zero with row less than one
         * @throws \Exception
         */
        public function testZeroExceptionRowsLessThanOne()
        {
            // Given
            $m = 0;
            $n = 2;

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            MatrixFactory::zero(m: $m, n: $n);
        }

        /**
         * @test         one
         * @dataProvider dataProviderForOne
         */
        public function testOne($m, $n, array $R)
        {
            // Given
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $M = MatrixFactory::one(m: $m, n: $n);
            } catch (Exception\BadDataException|Exception\MathException|Exception\OutOfBoundsException $e)
            {
            }

            // Then
            $this->assertEquals($R, $M);
        }

        /**
         * @test   one exception - rows less than one
         * @throws \Exception
         */
        public function testOneExceptionRowsLessThanOne()
        {
            $this->expectException(Exception\OutOfBoundsException::class);
            MatrixFactory::one(m: 0, n: 2);
        }

        /**
         * @test         eye
         * @dataProvider dataProviderForEye
         */
        public function testEye(int $m, int $n, int $k, int $x, array $R)
        {
            // Given
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $A = MatrixFactory::eye(m: $m, n: $n, k: $k, x: $x);
            } catch (Exception\BadDataException|Exception\MathException|Exception\OutOfBoundsException $e)
            {
            }

            // Then
            $this->assertEquals($R, $A);
            $this->assertEquals($R->getMatrix(), $A->getMatrix());

            // And
            $this->assertEquals($m, $R->getM());
            $this->assertEquals($n, $R->getN());
        }

        /**
         * @test         eye exceptions
         * @dataProvider dataProviderForEyeExceptions
         */
        public function testEyeExceptions(int $m, int $n, int $k, int $x)
        {
            $this->expectException(Exception\OutOfBoundsException::class);
            try
            {
                $A = MatrixFactory::eye(m: $m, n: $n, k: $k, x: $x);
            } catch (Exception\BadDataException|Exception\MathException|Exception\OutOfBoundsException $e)
            {
            }
        }

        /**
         * @test         hilbert creates the expected Hilbert matrix
         * @dataProvider dataProviderForHilbertMatrix
         *
         * @param int   $n
         * @param array $H
         *
         * @throws       \Exception
         */
        public function testHilbertMatrix(int $n, array $H)
        {
            // Given
            $H = MatrixFactory::create(A: $H);

            // When
            $sut = MatrixFactory::hilbert(n: $n);

            // Then
            $this->assertEquals($H, $sut);
        }

        /**
         * @test   Hilbert exception when n is less than zero
         * @throws \Exception
         */
        public function testHilbertExceptionNLessThanZero()
        {
            // Given
            $n = -1;

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            MatrixFactory::hilbert(n: -1);
        }

        /**
         * @test   Creating a random matrix of a specific size
         * @throws \Exception
         */
        public function testRandomMatrix()
        {
            // Given
            for ($m = 1; $m < 5; $m++)
            {
                {
                    for ($n = 1; $n < 5; $n++)
                    {
                        // When
                        $A = MatrixFactory::random($m, $n);

                        // Then
                        $this->assertEquals($m, $A->getM());
                        $this->assertEquals($n, $A->getN());

                        // And
                        $A->walk(function ($element) {
                            $this->assertTrue(is_int($element));
                        });
                    }
                }
            }
        }

        /**
         * @test         create ObjectMatrix
         * @dataProvider dataProviderForObjectMatrix
         *
         * @param array $A
         */
        public function testCreateObjectMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(ObjectMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         create ObjectSquareMatrix
         * @dataProvider dataProviderForObjectSquareMatrix
         *
         * @param array $A
         */
        public function testCreateObjectSquareMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(ObjectSquareMatrix::class, $A);
            $this->assertInstanceOf(ObjectMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }

        /**
         * @test         createFromColumnVector
         * @dataProvider dataProviderForCreateFromColumnVector
         *
         * @param array $V
         * @param array $expected
         */
        public function testConstructor(array $V, array $expected)
        {
            // Given
            try
            {
                $expected = new NumericMatrix(A: $expected);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            try
            {
                $A = MatrixFactory::createFromColumnVector(A: $V);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertInstanceOf(NumericMatrix::class, $A);

            // And
            $this->assertEquals($expected->getMatrix(), $A->getMatrix());
        }

        /**
         * @test createFromColumnVector failure due to not being a column vector
         */
        public function testConstructionFailure()
        {
            // Given
            $A = [
                [1, 2, 3],
                [2, 3, 4],
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $R = MatrixFactory::createFromColumnVector(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         createFromRowVector
         * @dataProvider dataProviderForConstructor
         *
         * @param array $V
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testCreateFromRowVector(array $V, array $expected)
        {
            // Given
            $expected = new NumericMatrix(A: $expected);

            $A = MatrixFactory::createFromRowVector(A: $V);

            // Then
            $this->assertInstanceOf(NumericMatrix::class, $A);

            // And
            $this->assertEquals($expected->getMatrix(), $A->getMatrix());
        }

        /**
         * @test createFromRowVector failure due to not being a row vector
         */
        public function testCreateFromRowVectorFailure()
        {
            // Given
            $A = [
                [1, 2, 3],
                [2, 3, 4],
            ];

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $R = MatrixFactory::createFromRowVector(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         create ComplexMatrix
         * @dataProvider dataProviderForComplexObjectMatrix
         *
         * @param array $A
         */
        public function testCreateComplexObjectMatrix(array $A)
        {
            // When
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(ComplexMatrix::class, $A);
            $this->assertInstanceOf(ObjectMatrix::class, $A);
            $this->assertInstanceOf(Matrix::class, $A);
        }
    }
