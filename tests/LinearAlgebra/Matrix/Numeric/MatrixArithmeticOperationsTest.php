<?php

    namespace MathPHP\Tests\LinearAlgebra\MatrixNumeric;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\LinearAlgebra\NumericSquareMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    class MatrixArithmeticOperationsTest extends TestCase {
        public static function dataProviderForAdd(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    [
                        [2, 3, 4],
                        [3, 4, 5],
                        [4, 5, 6],
                    ],
                ],
                [
                    [
                        [0, 1, 2],
                        [9, 8, 7],
                    ],
                    [
                        [6, 5, 4],
                        [3, 4, 5],
                    ],
                    [
                        [6, 6, 6],
                        [12, 12, 12],
                    ],
                ],
            ];
        }

        public static function dataProviderForDirectSum(): array
        {
            return [
                [
                    [
                        [1, 3, 2],
                        [2, 3, 1],
                    ],
                    [
                        [1, 6],
                        [0, 1],
                    ],
                    [
                        [1, 3, 2, 0, 0],
                        [2, 3, 1, 0, 0],
                        [0, 0, 0, 1, 6],
                        [0, 0, 0, 0, 1],
                    ],
                ],
            ];
        }

        public static function dataProviderKroneckerSum(): array
        {
            return [
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    [
                        [2, 2, 3, 2, 0, 0],
                        [4, 6, 6, 0, 2, 0],
                        [7, 8, 10, 0, 0, 2],
                        [3, 0, 0, 5, 2, 3],
                        [0, 3, 0, 4, 9, 6],
                        [0, 0, 3, 7, 8, 13],
                    ],
                ],
                [
                    [
                        [1, 1],
                        [1, 1],
                    ],
                    [
                        [1, 1],
                        [1, 1],
                    ],
                    [
                        [2, 1, 1, 0],
                        [1, 2, 0, 1],
                        [1, 0, 2, 1],
                        [0, 1, 1, 2],
                    ],
                ],
                [
                    [
                        [1, 1],
                        [1, 1],
                    ],
                    [
                        [2, 3],
                        [4, 5],
                    ],
                    [
                        [3, 3, 1, 0],
                        [4, 6, 0, 1],
                        [1, 0, 3, 3],
                        [0, 1, 4, 6],
                    ],
                ],
                [
                    [
                        [2, 3],
                        [4, 5],
                    ],
                    [
                        [1, 1],
                        [1, 1],
                    ],
                    [
                        [3, 1, 3, 0],
                        [1, 3, 0, 3],
                        [4, 0, 6, 1],
                        [0, 4, 1, 6],
                    ],
                ],
            ];
        }

        public static function dataProviderForKroneckerSumSquareMatrixException(
        ): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                    [
                        [1, 2],
                        [2, 3],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [2, 3],
                    ],
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [2, 3],
                        [4, 5],
                    ],
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                ],
            ];
        }

        public static function dataProviderForSubtract(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [
                        [1, 1, 1],
                        [1, 1, 1],
                        [1, 1, 1],
                    ],
                    [
                        [0, 1, 2],
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                ],
                [
                    [
                        [0, 1, 2],
                        [9, 8, 7],
                    ],
                    [
                        [6, 5, 4],
                        [3, 4, 5],
                    ],
                    [
                        [-6, -4, -2],
                        [6, 4, 2],
                    ],
                ],
            ];
        }

        public static function dataProviderForMultiply(): array
        {
            return [
                [
                    [
                        [0],
                    ],
                    [
                        [0],
                    ],
                    [
                        [0],
                    ],
                ],
                [
                    [
                        [0],
                    ],
                    [
                        [1],
                    ],
                    [
                        [0],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    [
                        [0],
                    ],
                    [
                        [0],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    [
                        [2],
                    ],
                    [
                        [2],
                    ],
                ],
                [
                    [
                        [2],
                    ],
                    [
                        [1],
                    ],
                    [
                        [2],
                    ],
                ],
                [
                    [
                        [2],
                    ],
                    [
                        [3],
                    ],
                    [
                        [6],
                    ],
                ],
                [
                    [
                        [3],
                    ],
                    [
                        [2],
                    ],
                    [
                        [6],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    [
                        [1, 2, 3],
                    ],
                    [
                        [1, 2, 3],
                    ],
                ],
                [
                    [
                        [0],
                    ],
                    [
                        [1, 2, 3],
                    ],
                    [
                        [0, 0, 0],
                    ],
                ],
                [
                    [
                        [4],
                    ],
                    [
                        [1, 2, 3],
                    ],
                    [
                        [4, 8, 12],
                    ],
                ],
                [
                    [
                        [4],
                    ],
                    [
                        [1, -3, 2],
                    ],
                    [
                        [4, -12, 8],
                    ],
                ],
                [
                    [
                        [1, -3, 2],
                    ],
                    [
                        [2],
                        [-1],
                        [0],
                    ],
                    [
                        [5],
                    ],
                ],
                [
                    [
                        [1],
                        [-3],
                        [2],
                    ],
                    [
                        [-1, -2],
                    ],
                    [
                        [-1, -2],
                        [3, 6],
                        [-2, -4],
                    ],
                ],
                [
                    [
                        [0, 1, 0],
                    ],
                    [
                        [1, -2, 2],
                        [4, 2, 0],
                        [1, 2, 3],
                    ],
                    [
                        [4, 2, 0],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [0, 0],
                        [0, 0],
                    ],
                    [
                        [0, 0],
                        [0, 0],
                    ],
                ],
                [
                    [
                        [0, 0],
                        [0, 0],
                    ],
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [0, 0],
                        [0, 0],
                    ],
                ],
                [
                    [
                        [0, 1],
                        [0, 0],
                    ],
                    [
                        [0, 0],
                        [1, 0],
                    ],
                    [
                        [1, 0],
                        [0, 0],
                    ],
                ],
                [
                    [
                        [0, 0],
                        [1, 0],
                    ],
                    [
                        [0, 1],
                        [0, 0],
                    ],
                    [
                        [0, 0],
                        [0, 1],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [5, 6],
                        [7, 8],
                    ],
                    [
                        [19, 22],
                        [43, 50],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [2, 0],
                        [1, 2],
                    ],
                    [
                        [4, 4],
                        [10, 8],
                    ],
                ],
                [
                    [
                        [2, 0],
                        [1, 2],
                    ],
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [2, 4],
                        [7, 10],
                    ],
                ],
                [
                    [
                        [1, 0, -2],
                        [0, 3, -1],
                    ],
                    [
                        [0, 3],
                        [-2, -1],
                        [0, 4],
                    ],
                    [
                        [0, -5],
                        [-6, -7],
                    ],
                ],
                [
                    [
                        [2, 3],
                        [1, -5],
                    ],
                    [
                        [4, 3, 6],
                        [1, -2, 3],
                    ],
                    [
                        [11, 0, 21],
                        [-1, 13, -9],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                    ],
                    [
                        [7, 8],
                        [9, 10],
                        [11, 12],
                    ],
                    [
                        [58, 64],
                        [139, 154],
                    ],
                ],
                [
                    [
                        [3, 4, 2],
                    ],
                    [
                        [13, 9, 7, 15],
                        [8, 7, 4, 6],
                        [6, 4, 0, 3],
                    ],
                    [
                        [83, 63, 37, 75],
                    ],
                ],
                [
                    [
                        [0, 1, 2],
                        [3, 4, 5],
                    ],
                    [
                        [6, 7],
                        [8, 9],
                        [10, 11],
                    ],
                    [
                        [28, 31],
                        [100, 112],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    [
                        [30, 36, 42],
                        [66, 81, 96],
                        [102, 126, 150],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4, 5, 6, 7, 8],
                        [2, 3, 4, 5, 6, 7, 8, 9],
                        [3, 4, 5, 6, 7, 8, 9, 1],
                        [4, 5, 6, 7, 8, 9, 1, 2],
                        [5, 6, 7, 8, 9, 1, 2, 3],
                        [6, 7, 8, 9, 1, 2, 3, 4],
                        [7, 8, 9, 1, 2, 3, 4, 5],
                        [8, 9, 1, 2, 3, 4, 5, 6],
                        [9, 1, 2, 3, 4, 5, 6, 7],
                    ],
                    [
                        [7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8],
                        [8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        [9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1],
                        [1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2],
                        [2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3],
                        [3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4],
                        [4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5],
                        [5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6],
                    ],
                    [
                        [150, 159, 177, 204, 240, 204, 177, 159, 150, 150, 159],
                        [189, 197, 214, 240, 284, 247, 219, 200, 190, 189, 197],
                        [183, 181, 188, 204, 247, 281, 243, 214, 194, 183, 181],
                        [186, 174, 171, 177, 219, 243, 276, 237, 207, 186, 174],
                        [198, 176, 163, 159, 200, 214, 237, 269, 229, 198, 176],
                        [219, 187, 164, 150, 190, 194, 207, 229, 260, 219, 187],
                        [249, 207, 174, 150, 189, 183, 186, 198, 219, 249, 207],
                        [207, 236, 193, 159, 197, 181, 174, 176, 187, 207, 236],
                        [174, 193, 221, 177, 214, 188, 171, 163, 164, 174, 193],
                    ],
                ],
                [
                    [
                        [1.4, 5.3, 4.8],
                        [3.2, 2.3, 9.05],
                        [9.54, 0.2, 1.85],
                    ],
                    [
                        [3.5, 5.6, 6.7],
                        [6.5, 4.2, 9.05],
                        [0.6, 0.236, 4.5],
                    ],
                    [
                        [42.23, 31.2328, 78.945],
                        [31.58, 29.7158, 82.980],
                        [35.80, 54.7006, 74.053],
                    ],
                ],
            ];
        }

        public static function dataProviderForMultiplyVector(): array
        {
            return [
                [
                    [
                        [1],
                    ],
                    [1],
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [2],
                    ],
                    [3],
                    [
                        [6],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [2, 3],
                    ],
                    [4, 5],
                    [
                        [14],
                        [23],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [1, 2, 3],
                    [
                        [14],
                        [20],
                        [26],
                    ],
                ],
                [
                    [
                        [3, 6, 5],
                        [1, 7, 5],
                        [2, 3, 2],
                    ],
                    [1, 5, 4],
                    [
                        [53],
                        [56],
                        [25],
                    ],
                ],
                [
                    [
                        [1, 1, 1],
                        [2, 2, 2],
                    ],
                    [1, 2, 3],
                    [
                        [6],
                        [12],
                    ],
                ],
                [
                    [
                        [1, 1, 1],
                        [2, 2, 2],
                        [3, 3, 3],
                        [4, 4, 4],
                    ],
                    [1, 2, 3],
                    [
                        [6],
                        [12],
                        [18],
                        [24],
                    ],
                ],
            ];
        }

        public static function dataProviderForScalarMultiply(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    3,
                    [
                        [3, 6, 9],
                        [6, 9, 12],
                        [9, 12, 15],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                    ],
                    3,
                    [
                        [3, 6, 9],
                    ],
                ],
                [
                    [
                        [1],
                        [2],
                        [3],
                    ],
                    3,
                    [
                        [3],
                        [6],
                        [9],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    3,
                    [
                        [3],
                    ],
                ],
            ];
        }

        /**
         * @return array [A, −A]
         */
        public static function dataProviderForNegate(): array
        {
            return [
                [
                    [
                        [0],
                    ],
                    [
                        [0],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    [
                        [-1],
                    ],
                ],
                [
                    [
                        [-1],
                    ],
                    [
                        [1],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [-1, -2],
                        [-3, -4],
                    ],
                ],
                [
                    [
                        [1, -2, 3],
                        [-4, 5, -6],
                        [7, -8, 9],
                    ],
                    [
                        [-1, 2, -3],
                        [4, -5, 6],
                        [-7, 8, -9],
                    ],
                ],
            ];
        }

        public static function dataProviderForScalarDivide(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    3,
                    [
                        [1 / 3, 2 / 3, 1],
                        [2 / 3, 1, 4 / 3],
                        [1, 4 / 3, 5 / 3],
                    ],
                ],
                [
                    [
                        [3, 6, 9],
                    ],
                    3,
                    [
                        [1, 2, 3],
                    ],
                ],
                [
                    [
                        [1],
                        [2],
                        [3],
                    ],
                    3,
                    [
                        [1 / 3],
                        [2 / 3],
                        [1],
                    ],
                ],
                [
                    [
                        [1],
                    ],
                    3,
                    [
                        [1 / 3],
                    ],
                ],
            ];
        }

        public static function dataProviderForHadamardProduct(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [
                        [1, 4, 9],
                        [4, 9, 16],
                        [9, 16, 25],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    [
                        [6, 6, 4],
                        [8, 7, 8],
                        [3, 1, 7],
                    ],
                    [
                        [6, 12, 12],
                        [16, 21, 32],
                        [9, 4, 35],
                    ],
                ],
            ];
        }

        public static function dataProviderForKroneckerProduct(): array
        {
            return [
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [0, 5],
                        [6, 7],
                    ],
                    [
                        [0, 5, 0, 10],
                        [6, 7, 12, 14],
                        [0, 15, 0, 20],
                        [18, 21, 24, 28],
                    ],
                ],
                [
                    [
                        [1, 1],
                        [1, -1],
                    ],
                    [
                        [1, 1],
                        [1, -1],
                    ],
                    [
                        [1, 1, 1, 1],
                        [1, -1, 1, -1],
                        [1, 1, -1, -1],
                        [1, -1, -1, 1],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                    ],
                    [
                        [7, 8],
                        [9, 10],
                    ],
                    [
                        [7, 8, 14, 16, 21, 24],
                        [9, 10, 18, 20, 27, 30],
                        [28, 32, 35, 40, 42, 48],
                        [36, 40, 45, 50, 54, 60],
                    ],
                ],
                [
                    [
                        [2, 3],
                        [5, 4],
                    ],
                    [
                        [5, 5],
                        [4, 4],
                        [2, 9],
                    ],
                    [
                        [10, 10, 15, 15],
                        [8, 8, 12, 12],
                        [4, 18, 6, 27],
                        [25, 25, 20, 20],
                        [20, 20, 16, 16],
                        [10, 45, 8, 36],
                    ],
                ],
                [
                    [
                        [2, 3],
                        [5, 4],
                    ],
                    [
                        [5, 4, 2],
                        [5, 4, 9],
                    ],
                    [
                        [10, 8, 4, 15, 12, 6],
                        [10, 8, 18, 15, 12, 27],
                        [25, 20, 10, 20, 16, 8],
                        [25, 20, 45, 20, 16, 36],
                    ],
                ],

            ];
        }

        /**
         * @test         add
         * @dataProvider dataProviderForAdd
         */
        public function testAdd(array $A, array $B, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = MatrixFactory::create(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $R2 = $A->add($B);
            } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($R, $R2);
        }

        /**
         * @test   add exception for rows
         * @throws \Exception
         */
        public function testAddExceptionRows()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2],
                [2, 3],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            //  WHen
            $A->add($B);
        }

        /**
         * @test   add exception for columns
         * @throws \Exception
         */
        public function testAddExceptionColumns()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2],
                [2, 3],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->add($B);
        }

        /**
         * @test         directSum
         * @dataProvider dataProviderForDirectSum
         */
        public function testDirectSum(array $A, array $B, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = MatrixFactory::create(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $R2 = $A->directSum(B: $B);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($R, $R2);
        }

        /**
         * @test         kroneckerSum returns the expected SquareMatrix
         * @dataProvider dataProviderKroneckerSum
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         */
        public function testKroneckerSum(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = new NumericSquareMatrix(A: $A);
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $B = new NumericSquareMatrix(A: $B);
            } catch (Exception\MathException $e)
            {
            }
            try
            {
                $R = new NumericSquareMatrix(A: $expected);
            } catch (Exception\MathException $e)
            {
            }

            // When
            try
            {
                $A⊕B = $A->kroneckerSum(B: $B);
            } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($R, $A⊕B);
            $this->assertEquals($R->getMatrix(), $A⊕B->getMatrix());
            $this->assertInstanceOf(NumericSquareMatrix::class, $A⊕B);
        }

        /**
         * @test         kronecerSum throws a MatrixException if one of the matrices is not square
         * @dataProvider dataProviderForKroneckerSumSquareMatrixException
         *
         * @param $A
         * @param $B
         */
        public function testKroneckerSumSquareMatrixException($A, $B)
        {
            // Given
            try
            {
                $A = new NumericMatrix(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $B = new NumericMatrix(A: $B);
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            try
            {
                $A⊕B = $A->kroneckerSum(B: $B);
            } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         subtract
         * @dataProvider dataProviderForSubtract
         */
        public function testSubtract(array $A, array $B, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = MatrixFactory::create(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $R2 = $A->subtract($B);
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($R, $R2);
        }

        /**
         * @test   subtract exception for rows
         * @throws \Exception
         */
        public function testSubtractExceptionRows()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2],
                [2, 3],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->subtract($B);
        }

        /**
         * @test   subtract exception for columns
         * @throws \Exception
         */
        public function testSubtractExceptionColumns()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2],
                [2, 3],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->subtract($B);
        }

        /**
         * @test         multiplication
         * @dataProvider dataProviderForMultiply
         *
         * @param array $A
         * @param array $B
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testMultiply(array $A, array $B, array $expected)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $expected = MatrixFactory::create(A: $expected);

            // When
            $R = $A->multiply($B);

            // Then
            $this->assertEqualsWithDelta($expected, $R, 0.00001);
            $this->assertTrue($R->isEqual($expected));
        }

        /**
         * @test         multiply vector
         * @dataProvider dataProviderForMultiplyVector
         */
        public function testMultiplyVector(array $A, array $B, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new Vector(A: $B);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $R2 = $A->multiply($B);
            } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($R, $R2);
        }

        /**
         * @test   multiple exception
         * @throws \Exception
         */
        public function testMultiplyExceptionDimensionsDoNotMatch()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->multiply($B);
        }

        /**
         * @test   multiple exception
         * @throws \Exception
         */
        public function testMultiplyExceptionNotMatrixOrVector()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);
            $B = [
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ];

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            $A->multiply($B);
        }

        /**
         * @test         scalarMultiply
         * @dataProvider dataProviderForScalarMultiply
         */
        public function testScalarMultiply(array $A, $k, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $kA = $A->scalarMultiply(λ: $k);
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($R, $kA);
        }

        /**
         * @test         negate
         * @dataProvider dataProviderForNegate
         *
         * @param array $A
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testNegate(array $A, array $expected)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expected = MatrixFactory::create(A: $expected);

            // When
            $−A = $A->negate();

            // Then
            $this->assertEquals($expected, $−A);
        }

        /**
         * @test         scalarDivide
         * @dataProvider dataProviderForScalarDivide
         */
        public function testScalarDivide(array $A, $k, array $R)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $R = MatrixFactory::create(A: $R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $divided = $A->scalarDivide(λ: $k);
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($R, $divided);
        }

        /**
         * @test   scalarDivide by zero
         * @throws \Exception
         */
        public function testScalarDivideByZero()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);

            // Then
            $this->expectException(Exception\BadParameterException::class);

            // When
            $A->scalarDivide(λ: 0);
        }

        /**
         * @test         hadamardProduct
         * @dataProvider dataProviderForHadamardProduct
         */
        public function testHadamardProduct(array $A, array $B, array $expected)
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $B = MatrixFactory::create(A: $B);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            try
            {
                $expected = MatrixFactory::create(A: $expected);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $A∘B = $A->hadamardProduct(B: $B);
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($expected, $A∘B);
        }

        /**
         * @test   hadamardProduct dimensions don't match
         * @throws \Exception
         */
        public function testHadamardProductDimensionsDoNotMatch()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
            ]);
            $B = MatrixFactory::create(A: [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->hadamardProduct(B: $B);
        }

        /**
         * @test         kroneckerProduct
         * @dataProvider dataProviderForKroneckerProduct
         */
        public function testKroneckerProduct(
            array $A,
            array $B,
            array $expected
        ) {
            // Given
            try
            {
                $A = new NumericMatrix(A: $A);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $B = new NumericMatrix(A: $B);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $expected = new NumericMatrix(A: $expected);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $A⊗B = $A->kroneckerProduct(B: $B);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                }
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertEquals($expected->getMatrix(), $A⊗B->getMatrix());
        }
    }
