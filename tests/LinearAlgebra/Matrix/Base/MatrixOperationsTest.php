<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Base;

    use MathPHP\Exception;
    use MathPHP\Expression\Polynomial;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use PHPUnit\Framework\TestCase;

    class MatrixOperationsTest extends TestCase {
        public static function dataProviderForTranspose(): array
        {
            return [
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
                        [5, 4, 3],
                        [4, 0, 4],
                        [7, 10, 3],
                    ],
                    [
                        [5, 4, 7],
                        [4, 0, 10],
                        [3, 4, 3],
                    ],
                ],
                [
                    [
                        [5, 4],
                        [4, 0],
                        [7, 10],
                        [-1, 8],
                    ],
                    [
                        [5, 4, 7, -1],
                        [4, 0, 10, 8],
                    ],
                ],
            ];
        }

        public static function dataProviderForMinorMatrix(): array
        {
            return [
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    0,
                    0,
                    [
                        [0, 5],
                        [9, 11],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    0,
                    1,
                    [
                        [3, 5],
                        [-1, 11],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    0,
                    2,
                    [
                        [3, 0],
                        [-1, 9],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    1,
                    0,
                    [
                        [4, 7],
                        [9, 11],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    1,
                    1,
                    [
                        [1, 7],
                        [-1, 11],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    1,
                    2,
                    [
                        [1, 4],
                        [-1, 9],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    2,
                    0,
                    [
                        [4, 7],
                        [0, 5],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    2,
                    1,
                    [
                        [1, 7],
                        [3, 5],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    2,
                    2,
                    [
                        [1, 4],
                        [3, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForLeadingPrincipalMinor(): array
        {
            return [
                [
                    [
                        [1],
                    ],
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [4, 5],
                    ],
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [4, 5],
                    ],
                    2,
                    [
                        [1, 2],
                        [4, 5],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    2,
                    [
                        [1, 2],
                        [4, 5],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    3,
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4],
                        [5, 6, 7, 8],
                        [9, 0, 1, 2],
                        [3, 4, 5, 6],
                    ],
                    1,
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4],
                        [5, 6, 7, 8],
                        [9, 0, 1, 2],
                        [3, 4, 5, 6],
                    ],
                    2,
                    [
                        [1, 2],
                        [5, 6],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4],
                        [5, 6, 7, 8],
                        [9, 0, 1, 2],
                        [3, 4, 5, 6],
                    ],
                    3,
                    [
                        [1, 2, 3],
                        [5, 6, 7],
                        [9, 0, 1],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4],
                        [5, 6, 7, 8],
                        [9, 0, 1, 2],
                        [3, 4, 5, 6],
                    ],
                    4,
                    [
                        [1, 2, 3, 4],
                        [5, 6, 7, 8],
                        [9, 0, 1, 2],
                        [3, 4, 5, 6],
                    ],
                ],
            ];
        }

        public static function dataProviderForMinor(): array
        {
            return [
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    0,
                    0,
                    -45,
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    0,
                    1,
                    38,
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    1,
                    2,
                    13,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    0,
                    0,
                    1,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    0,
                    1,
                    -6,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    0,
                    2,
                    -13,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    1,
                    0,
                    0,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    1,
                    1,
                    0,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    1,
                    2,
                    0,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    2,
                    0,
                    1,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    2,
                    1,
                    -6,
                ],
                [
                    [
                        [1, 2, 1],
                        [6, -1, 0],
                        [-1, -2, -1],
                    ],
                    2,
                    2,
                    -13,
                ],
            ];
        }

        public static function dataProviderForSubMatrix(): array
        {
            return [
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    [1, 1, 2, 2],
                    [
                        [0, 5],
                        [9, 11],
                    ],
                ],
                [
                    [
                        [1, 4, 7],
                        [3, 0, 5],
                        [-1, 9, 11],
                    ],
                    [0, 0, 1, 0],
                    [
                        [1],
                        [3],
                    ],
                ],
                [
                    [
                        [1, 4, 7, 30],
                        [3, 0, 5, 4],
                        [-1, 9, 11, 10],
                    ],
                    [0, 1, 1, 3],
                    [
                        [4, 7, 30],
                        [0, 5, 4],
                    ],
                ],
            ];
        }

        public static function dataProviderForInsert(): array
        {
            return [
                [
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    [
                        [0, 0],
                        [0, 0],
                    ],
                    1,
                    1,
                    [
                        [1, 1, 1],
                        [1, 0, 0],
                        [1, 0, 0],
                    ],
                ],
                [
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    [
                        [0, 0],
                    ],
                    1,
                    1,
                    [
                        [1, 1, 1],
                        [1, 0, 0],
                        [1, 1, 1],
                    ],
                ],
                [
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    [
                        [0, 0],
                    ],
                    2,
                    1,
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 0, 0],
                    ],
                ],
            ];
        }

        /**
         * @test         transpose
         * @dataProvider dataProviderForTranspose
         *
         * @param array $A
         * @param array $R
         *
         * @throws       \Exception
         */
        public function testTranspose(array $A, array $R)
        {
            // Given
            $A = MatrixFactory::create($A);
            $R = MatrixFactory::create($R);

            // When
            $Aᵀ = $A->transpose();

            // Then
            $this->assertEquals($R->getMatrix(), $Aᵀ->getMatrix());
        }

        /**
         * @test         transpose of transpose is the original matrix
         * @dataProvider dataProviderForTranspose
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testTransposeOfTransposeIsOriginalMatrix(array $A)
        {
            // Given
            $A = MatrixFactory::create($A);

            // When
            $Aᵀ = $A->transpose();
            $Aᵀᵀ = $Aᵀ->transpose();

            // Then
            $this->assertEquals($A->getMatrix(), $Aᵀᵀ->getMatrix());
        }

        /**
         * @test         minorMatrix
         * @dataProvider dataProviderForMinorMatrix
         */
        public function testMinorMatrix(array $A, int $mᵢ, int $nⱼ, array $Mᵢⱼ)
        {
            // Given
            try
            {
                $A = MatrixFactory::create($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $Mᵢⱼ = MatrixFactory::create($Mᵢⱼ);
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
                $minor = $A->minorMatrix($mᵢ, $nⱼ);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($Mᵢⱼ, $minor);
        }

        /**
         * @test minorMatrix exception - bad row
         */
        public function testMinorMatrixExceptionBadRow()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minorMatrix(4, 1);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test minorMatrix exception - bad column
         */
        public function testMinorMatrixExceptionBadColumn()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minorMatrix(1, 4);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test minorMatrix exception - not square
         */
        public function testMinorMatrixExceptionNotSquare()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3, 4],
                    [2, 3, 4, 4],
                    [3, 4, 5, 4],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minorMatrix(1, 1);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test         leadingPrincipalMinor returns the expected SquareMatrix
         * @dataProvider dataProviderForLeadingPrincipalMinor
         *
         * @param array $A
         * @param int   $k
         * @param array $R
         */
        public function testLeadingPrincipalMinor(array $A, int $k, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create($R);
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
                $minor = $A->leadingPrincipalMinor($k);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\OutOfBoundsException $e)
            {
            }

            // Then
            $this->assertEquals($R, $minor);
        }

        /**
         * @test leadingPrincipalMinor throws an OutOfBoundsException when k is < 0.
         */
        public function testLeadingPrincipalMinorExceptionKLessThanZero()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            try
            {
                $R = $A->leadingPrincipalMinor(-1);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\OutOfBoundsException $e)
            {
            }
        }

        /**
         * @test leadingPrincipalMinor throws an OutOfBoundsException when k is > n.
         */
        public function testLeadingPrincipalMinorExceptionKGreaterThanN()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\OutOfBoundsException::class);

            // When
            try
            {
                $R = $A->leadingPrincipalMinor($A->getN() + 1);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\OutOfBoundsException $e)
            {
            }
        }

        /**
         * @test leadingPrincipalMinor throws a MatrixException if the Matrix is not square.
         */
        public function testLeadingPrincipalMinorExceptionMatrixNotSquare()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [4, 5, 6],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $R = $A->leadingPrincipalMinor(2);
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\OutOfBoundsException $e)
            {
            }
        }

        /**
         * @test         minor
         * @dataProvider dataProviderForMinor
         */
        public function testMinor(array $A, int $mᵢ, int $nⱼ, $Mᵢⱼ)
        {
            // Given
            try
            {
                $A = MatrixFactory::create($A);
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
                $minor = $A->minor($mᵢ, $nⱼ);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($Mᵢⱼ, $minor);
        }

        /**
         * @test minor exception - bad row
         */
        public function testMinorExceptionBadRow()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minor(4, 1);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test minor exception - bad column
         */
        public function testMinorExceptionBadColumn()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minor(1, 4);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test minor exception - not square
         */
        public function testMinorExceptionNotSquare()
        {
            // Given
            try
            {
                $A = MatrixFactory::create([
                    [1, 2, 3, 4],
                    [2, 3, 4, 4],
                    [3, 4, 5, 4],
                ]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->minor(1, 1);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        /**
         * @test         submatrix
         * @dataProvider dataProviderForSubmatrix
         *
         * @param array $data
         * @param array $params
         * @param array $result
         *
         * @throws       \Exception
         */
        public function testSubmatrix(array $data, array $params, array $result)
        {
            // Given
            $M = new NumericMatrix($data);
            $expectedMatrix = new NumericMatrix($result);

            // When
            $R = $M->submatrix(...$params);

            // Then
            $this->assertEquals($expectedMatrix->getMatrix(), $R->getMatrix());
        }

        /**
         * @test   submatrix exception - bad row
         * @throws \Exception
         */
        public function testSubmatrixExceptionBadRow()
        {
            // Given
            $A = MatrixFactory::create([
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);
            $this->expectExceptionMessage('Specified Matrix row does not exist');

            // When
            $A->submatrix(0, 0, 4, 1);
        }

        /**
         * @test   submatrix exception - bad column
         * @throws \Exception
         */
        public function testSubMatrixExceptionBadColumn()
        {
            // Given
            $A = MatrixFactory::create([
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);
            $this->expectExceptionMessage('Specified Matrix column does not exist');

            // When
            $A->submatrix(0, 0, 1, 4);
        }

        /**
         * @test   submatrix exception - wrong row order
         * @throws \Exception
         */
        public function testSubMatrixWrongRowOrder()
        {
            // Given
            $A = MatrixFactory::create([
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);
            $this->expectExceptionMessage('Ending row must be greater than beginning row');

            // When
            $A->submatrix(2, 0, 1, 2);
        }

        /**
         * @test   submatrix exception - wrong column order
         * @throws \Exception
         */
        public function testSubMatrixWrongColumnOrder()
        {
            // Given
            $A = MatrixFactory::create([
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);
            $this->expectExceptionMessage('Ending column must be greater than the beginning column');

            // When
            $A->submatrix(0, 2, 1, 0);
        }

        /**
         * @test         insert returns the expected value
         * @dataProvider dataProviderForInsert
         */
        public function testInsert(
            array $A,
            array $B,
            int $m,
            int $n,
            $expected
        ) {
            // Given
            try
            {
                $A = MatrixFactory::create($A);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $B = MatrixFactory::create($B);
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
                $matrixWithInsertion = $A->insert($B, $m, $n);
            } catch (Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($expected, $matrixWithInsertion->getMatrix());
        }

        /**
         * @test   insert exception - Inner matrix exceeds bounds
         * @throws \Exception
         */
        public function testInsertMatrixExceedsBounds()
        {
            // Given
            $A = MatrixFactory::create([
                [1, 1, 1],
                [1, 1, 1],
                [1, 1, 1],
            ]);
            // And
            $B = MatrixFactory::create([
                [0, 0, 0],
            ]);
            // Then
            $this->expectException(Exception\MatrixException::class);
            // When
            $A->insert($B, 1, 1);
        }

        /**
         * @test   insert exception - matrix is not the same type.
         * @throws \Exception
         */
        public function testInsertExceptionTypeMismatch()
        {
            // Given
            $A = MatrixFactory::create([[1]]);
            $B = MatrixFactory::create([[new Polynomial([1, 1])]]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->insert($B, 0, 0);
        }
    }
