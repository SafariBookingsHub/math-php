<?php

    namespace MathPHP\Tests\LinearAlgebra\Eigen;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\Eigenvector;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use PHPUnit\Framework\TestCase;

    use function sqrt;

    use const M_SQRT1_2;
    use const M_SQRT3;

    class EigenvectorTest extends TestCase {
        public static function dataProviderForEigenvector(): array
        {
            return [
                [
                    [
                        [0, 1],
                        [-2, -3],
                    ],
                    [
                        [1 / sqrt(num: 5), M_SQRT1_2],
                        [-2 / sqrt(num: 5), -M_SQRT1_2],
                    ],
                ],
                [
                    [
                        [6, -1],
                        [2, 3],
                    ],
                    [
                        [M_SQRT1_2, 1 / sqrt(num: 5)],
                        [M_SQRT1_2, 2 / sqrt(num: 5)],
                    ],
                ],
                [
                    [
                        [-2, -4, 2],
                        [-2, 1, 2],
                        [4, 2, 5],
                    ],
                    [
                        [
                            1 / sqrt(num: 293),
                            2 / sqrt(num: 6),
                            2 / sqrt(num: 14),
                        ],
                        [
                            6 / sqrt(num: 293),
                            1 / sqrt(num: 6),
                            -3 / sqrt(num: 14),
                        ],
                        [
                            16 / sqrt(num: 293),
                            -1 / sqrt(num: 6),
                            -1 / sqrt(num: 14),
                        ],
                    ],
                ],
                [ // RREF is a zero matrix
                  [
                      [1, 0, 0],
                      [0, 1, 0],
                      [0, 0, 1],
                  ],
                  [
                      [1, 0, 0],
                      [0, 1, 0],
                      [0, 0, 1],
                  ],
                ],
                [ // Matrix has duplicate eigenvalues. One vector is on an axis.
                  [
                      [2, 0, 1],
                      [2, 1, 2],
                      [3, 0, 4],
                  ],
                  [
                      [1 / sqrt(num: 14), 0, M_SQRT1_2],
                      [2 / sqrt(num: 14), 1, 0],
                      [3 / sqrt(num: 14), 0, -1 * M_SQRT1_2],
                  ],
                ],
                [ // Matrix has duplicate eigenvalues. no solution on the axis
                  [
                      [2, 2, -3],
                      [2, 5, -6],
                      [3, 6, -8],
                  ],
                  [
                      [1 / sqrt(num: 14), 1 / M_SQRT3, 5 / sqrt(num: 42)],
                      [2 / sqrt(num: 14), 1 / M_SQRT3, -4 / sqrt(num: 42)],
                      [3 / sqrt(num: 14), 1 / M_SQRT3, -1 / sqrt(num: 42)],
                  ],
                ],
                [ // The top row of the rref has a solitary 1 in position 0,0
                  [
                      [4, 1, 2],
                      [0, 0, -2],
                      [2, 2, 5],
                  ],
                  [
                      [5 / sqrt(num: 65), 1 / 3, 0],
                      [-2 / sqrt(num: 65), 2 / 3, -2 / sqrt(num: 5)],
                      [6 / sqrt(num: 65), -2 / 3, 1 / sqrt(num: 5),],
                  ],
                ],
            ];
        }

        public static function dataProviderForIncorrectNumberOfEigenvectors(
        ): array
        {
            return [
                [
                    [
                        [0, 1],
                        [-2, -3],
                    ],
                    [1, 2, 3],
                ],
            ];
        }

        public static function dataProviderForEigenvectorNotAnEigenvector(
        ): array
        {
            return [
                [
                    [
                        [0, 1],
                        [-2, -3],
                    ],
                    [-2, 0],
                ],
                [
                    [
                        [0, 1],
                        [-2, -3],
                    ],
                    [0, -3],
                ],
            ];
        }

        /**
         * @test         eigenvector using closedFormPolynomialRootMethod returns the expected eigenvalues
         * @dataProvider dataProviderForEigenvector
         *
         * @param array $A
         * @param array $S
         *
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\MatrixException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function testEigenvectorsUsingClosedFormPolynomialRootMethod(
            array $A,
            array $S
        ) {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $eigenvectors = Eigenvector::eigenvectors(A: $A);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\OutOfBoundsException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($S, $eigenvectors->getMatrix(),
                0.0001);
            try
            {
                $this->assertEqualsWithDelta($S,
                    $A->eigenvectors()->getMatrix(),
                    0.0001);
            } catch (Exception\MatrixException|Exception\MathException $e)
            {
            }
        }

        /**
         * @test         eigenvector using closedFormPolynomialRootMethod returns the expected eigenvalues
         * @dataProvider dataProviderForEigenvector
         *
         * @param array $A
         * @param array $S
         */
        public function testEigenvectorsUsingClosedFormPolynomialRootMethodFromMatrix(
            array $A,
            array $S
        ) {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $eigenvectors = $A->eigenvectors();
            } catch (Exception\MatrixException|Exception\MathException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($S, $eigenvectors->getMatrix(),
                0.0001);
        }

        /**
         * @test eigenvectors throws a BadDataException when the matrix is not square
         */
        public function testEigenvectorMatrixNotCorrectSize()
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: [[1, 2]]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                try
                {
                    Eigenvector::eigenvectors(A: $A, eigenvalues: [0]);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\OutOfBoundsException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         eigenvectors throws a BadDataException when the array of eigenvales is too long or short
         * @dataProvider dataProviderForIncorrectNumberOfEigenvectors
         *
         * @param array $A
         * @param array $B
         *
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\MatrixException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function testIncorrectNumberOfEigenvectors(array $A, array $B)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                try
                {
                    Eigenvector::eigenvectors(A: $A, eigenvalues: $B);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\OutOfBoundsException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         eigenvectors throws a BadDataException when there is an incorrect eigenvalue provided
         * @dataProvider dataProviderForEigenvectorNotAnEigenvector
         *
         * @param array $A
         * @param array $B
         *
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\MatrixException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function testEigenvectorNotAnEigenvector(array $A, array $B)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                try
                {
                    Eigenvector::eigenvectors(A: $A, eigenvalues: $B);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\OutOfBoundsException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test Matrix eigenvectors throws a MatrixException if the eigenvalue method is not valid
         */
        public function testMatrixEigenvectorInvalidMethodException()
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            $invalidMethod = 'SecretMethod';

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A->eigenvectors(method: $invalidMethod);
            } catch (Exception\MatrixException|Exception\MathException $e)
            {
            }
        }
    }
