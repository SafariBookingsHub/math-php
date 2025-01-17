<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Base;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use PHPUnit\Framework\TestCase;

    class MatrixRowOperationsTest extends TestCase {
        public static function dataProviderForRowInterchange(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    0,
                    1,
                    [
                        [2, 3, 4],
                        [1, 2, 3],
                        [3, 4, 5],
                    ],
                ],
                [
                    [
                        [5, 5],
                        [4, 4],
                        [2, 7],
                        [9, 0],
                    ],
                    2,
                    3,
                    [
                        [5, 5],
                        [4, 4],
                        [9, 0],
                        [2, 7],
                    ],
                ],
                [
                    [
                        [5, 5],
                        [4, 4],
                        [2, 7],
                        [9, 0],
                    ],
                    1,
                    2,
                    [
                        [5, 5],
                        [2, 7],
                        [4, 4],
                        [9, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForRowExclude(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    0,
                    [
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    1,
                    [
                        [1, 2, 3],
                        [3, 4, 5],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                        [3, 4, 5],
                    ],
                    2,
                    [
                        [1, 2, 3],
                        [2, 3, 4],
                    ],
                ],
            ];
        }

        /**
         * @test         rowInterchange
         * @dataProvider dataProviderForRowInterchange
         *
         * @param array $A
         * @param int   $mᵢ
         * @param int   $mⱼ
         * @param array $expectedMatrix
         *
         * @throws       \Exception
         */
        public function testRowInterchange(
            array $A,
            int $mᵢ,
            int $mⱼ,
            array $expectedMatrix
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expectedMatrix = MatrixFactory::create(A: $expectedMatrix);

            // When
            $R = $A->rowInterchange($mᵢ, $mⱼ);

            // Then
            $this->assertEquals($expectedMatrix, $R);
        }

        /**
         * @test   rowInterchange on a row greater than m
         * @throws \Exception
         */
        public function testRowInterchangeExceptionRowGreaterThanM()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
                [3, 4, 5],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->rowInterchange(4, 5);
        }

        /**
         * @test         rowExclude
         * @dataProvider dataProviderForRowExclude
         *
         * @param array $A
         * @param int   $mᵢ
         * @param array $expectedMatrix
         *
         * @throws       \Exception
         */
        public function testRowExclude(array $A, int $mᵢ, array $expectedMatrix)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expectedMatrix = MatrixFactory::create(A: $expectedMatrix);

            // When
            $R = $A->rowExclude($mᵢ);

            // Then
            $this->assertEquals($expectedMatrix, $R);
        }

        /**
         * @test  rowExclude on a row that does not exist
         * @throws \Exception
         */
        public function testRowExcludeExceptionRowDoesNotExist()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [2, 3, 4],
                [4, 5, 6],
            ]);

            // Then
            $this->expectException(Exception\MatrixException::class);

            // When
            $A->rowExclude(-5);
        }
    }
