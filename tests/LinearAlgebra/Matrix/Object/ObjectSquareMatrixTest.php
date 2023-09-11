<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Object;

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
        public static function dataProviderConstructorException(): array
        {
            return [
                'rows have different types'    => [
                    [[new stdClass()]],
                    Exception\IncorrectTypeException::class,
                ],
                'columns have different types' => [
                    [
                        [new stdClass(), new Polynomial([1, 2, 3])],
                        [new stdClass(), new Polynomial([1, 2, 3])],
                    ],
                    Exception\IncorrectTypeException::class,
                ],
                'not square'                   => [
                    [
                        [new Polynomial([1, 2]), new Polynomial([2, 1])],
                    ],
                    Exception\MatrixException::class,
                ],
            ];
        }

        public static function dataProviderForArithmeticExceptions(): array
        {
            return [
                [ // Different Sizes
                  [
                      [new Polynomial([1, 2, 3]), new Polynomial([1, 2, 3])],
                      [new Polynomial([1, 2, 3]), new Polynomial([1, 2, 3])],
                  ],
                  MatrixFactory::create([[new Polynomial([1, 2, 3])]]),
                  Exception\MatrixException::class,
                ],
                [ // Different Types
                  [[new Polynomial([1, 2, 3])]],
                  new ObjectSquareMatrix([[new Complex(1, 2)]]),
                  Exception\IncorrectTypeException::class,
                ],
                [ // Not a Matrix
                  [[new Polynomial([1, 2, 3])]],
                  new Complex(1, 2),
                  Exception\IncorrectTypeException::class,
                ],
            ];
        }

        /**
         * @return array
         */
        public static function dataProviderisEqual()
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

        /**
         * @return array
         */
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

        /**
         * @return array
         */
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

        /**
         * @return array
         */
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

        /**
         * @return array
         */
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
            $A = new ObjectSquareMatrix($A);
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
            $A = new ObjectSquareMatrix($A);

            // Then
            $this->expectException($exception);

            // When
            $C = $A->add($B);
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
            $A = new ObjectSquareMatrix($A);

            // Then
            $this->expectException($exception);

            // When
            $C = $A->subtract($B);
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
            $A = new ObjectSquareMatrix($A);

            // Then
            $this->expectException($exception);

            // When
            $C = $A->multiply($B);
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
            $A = new ObjectSquareMatrix($A);
            $B = new ObjectSquareMatrix($B);

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
            $A = new ObjectSquareMatrix($A);
            $B = new ObjectSquareMatrix($B);
            $expected = new ObjectSquareMatrix($expected);

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
         */
        public function testMul(array $A, array $B, array $expected)
        {
            // Given
            $A = new ObjectSquareMatrix($A);
            $B = new ObjectSquareMatrix($B);

            // And
            $expected = matrixFactory::create($expected);

            // When
            $sum = $A->multiply($B);

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
            $A = new ObjectSquareMatrix($A);
            $B = new Vector($B);

            // When
            $sum = $A->multiply($B);

            // Then
            $expected = MatrixFactory::create($expected);
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
            $A = new ObjectSquareMatrix($A);

            // When
            $det = $A->det();

            // Then
            $this->assertEquals($det, $expected);

            // And when
            $det = $A->det();

            // Then
            $this->assertEquals($expected, $det);
        }
    }
