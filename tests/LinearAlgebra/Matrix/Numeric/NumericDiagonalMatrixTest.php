<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Numeric;

    use MathPHP\Exception\MatrixException;
    use MathPHP\LinearAlgebra\NumericDiagonalMatrix;
    use PHPUnit\Framework\TestCase;

    class NumericDiagonalMatrixTest extends TestCase {
        private NumericDiagonalMatrix $matrix;

        public function setUp(): void
        {
            // Given
            try
            {
                $this->matrix = new NumericDiagonalMatrix(A: [
                    [2, 0, 0],
                    [0, 2, 0],
                    [0, 0, 2],
                ]);
            } catch (MatrixException $e)
            {
            }
        }

        /**
         * @test isSymmetric
         */
        public function testIsSymmetric(): void
        {
            // When
            $isSymmetric = $this->matrix->isSymmetric();

            // Then
            $this->assertTrue($isSymmetric);
        }

        /**
         * @test isLowerTriangular
         */
        public function testIsLowerTriangular(): void
        {
            // When
            $isLowerTriangular = $this->matrix->isLowerTriangular();

            // Then
            $this->assertTrue($isLowerTriangular);
        }

        /**
         * @test isUpperTriangular
         */
        public function testIsUpperTriangular(): void
        {
            // When
            $isUpperTriangular = $this->matrix->isUpperTriangular();

            // Then
            $this->assertTrue($isUpperTriangular);
        }

        /**
         * @test isTriangular
         */
        public function testIsTriangular(): void
        {
            // When
            $isTriangular = $this->matrix->isTriangular();

            // Then
            $this->assertTrue($isTriangular);
        }

        /**
         * @test isDiagonal
         */
        public function testIsDiagonal(): void
        {
            // When
            $isDiagonal = $this->matrix->isDiagonal();

            // Then
            $this->assertTrue($isDiagonal);
        }

        /**
         * @test inverse
         */
        public function testInverse(): void
        {
            // Given
            $expected = [
                [1 / 2, 0, 0],
                [0, 1 / 2, 0],
                [0, 0, 1 / 2],
            ];

            // When
            $inverse = $this->matrix->inverse();

            // Then
            $this->assertEquals($expected, $inverse->getMatrix());
        }
    }
