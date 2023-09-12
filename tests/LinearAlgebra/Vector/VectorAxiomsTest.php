<?php

    namespace MathPHP\Tests\LinearAlgebra\Vector;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\Exception\VectorException;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    use function abs;
    use function array_fill;

    /**
     * Tests of Vector axioms
     * These tests don't test specific functions,
     * but rather matrix axioms which in term make use of multiple functions.
     * If all the Vector math is implemented properly, these tests should
     * all work out according to the axioms.
     *
     * Axioms tested:
     *  - Norms
     *    - |x|₂ ≤ |x|₁ ≤ √n |x|₂
     *    - |x|∞ ≤ |x|₂ ≤ √n |x|∞
     *    - |x|∞ ≤ |x|₁ ≤ √n |x|∞
     *  - Dot product
     *    - A⋅B = B⋅A
     *    - 0⋅A = A⋅0 = 0
     *  - Cross product
     *    - A x B = -(B x A)
     *    - A x 0 = 0
     *    - A x (B + C) = (A x B) + (A x C)
     *  - Cross product / dot product
     *    - (A x B) ⋅ A = 0
     *    - (A x B) ⋅ B = 0
     *    - A ⋅ (B x C) = (A x B) ⋅ C
     *    - A x (B x C) = (A ⋅ C)B - (A ⋅ B)C
     *  - Outer product
     *    - A⨂B = ABᵀ
     *    - A⨂B = AB (direct product)
     *  - Scalar multiplication
     *    - (c + d)A = cA + dA
     *    - c(A + B) = cA + cB
     *    - 1A = A
     *    - 0A = 0
     *    - -1A = -A
     *  - Perpendicular / Perp dot product
     *    - A⋅A⊥ = 0
     *    - A⊥⋅A = 0
     *    - A⋅A⊥ = -A⊥⋅A
     *  - Projections / Perps
     *    - projᵇA + perpᵇA = A
     *    - |projᵇA|² + |perpᵇA|² = |A|²
     *    - projᵇA ⋅ perpᵇA = 0
     *    - |projᵇA⊥ ⋅ perpᵇA| = |projᵇA| |perpᵇA|
     */
    class VectorAxiomsTest extends TestCase {
        public static function dataProviderForSingleVector(): array
        {
            return [
                [[0]],
                [[1]],
                [[1, 2]],
                [[1, 2, 3, 4, 5]],
                [[5, 2, 7, 4, 2, 7, 4]],
                [[-4, 6, 3, 7, -4, 5, -8, -11, 5, 0, 5, -2]],
                [[1, 0, 3, 5, 3, 0, 0, 9, 0]],
                [[34, 100, 4, 532, 6, 43, 78, 32, 853, 23, 532, 327]],
            ];
        }

        public static function dataProviderForTwoVectors(): array
        {
            return [
                [
                    [1],
                    [1],
                ],
                [
                    [1, 2],
                    [2, 3],
                ],
                [
                    [1, 2, 3, 4, 5],
                    [4, 5, 6, 7, 8],
                ],
                [
                    [3, -5, 2, -12, 4, 9, -4],
                    [-9, 4, 5, 6, -11, 2, -4],
                ],
                [
                    [1, 0, 3],
                    [0, 1, 9],
                ],
            ];
        }

        public static function dataProviderForDotProductZero(): array
        {
            return [
                [
                    [1],
                    [0],
                ],
                [
                    [1, 2],
                    [0, 0],
                ],
                [
                    [1, 2, 3],
                    [0, 0, 0],
                ],
                [
                    [5, 6, 7, 3, 4, 5, 6, 7, 8, 6, 5],
                    [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                ],
            ];
        }

        public static function dataProviderForCrossProductThreeVectors(): array
        {
            return [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [5, 6, 7],
                ],
                [
                    [1, 2, 3],
                    [4, -5, 6],
                    [5, 6, 7],
                ],
                [
                    [-1, 2, -3],
                    [4, -5, 6],
                    [5, 6, 7],
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                [
                    [4, 5, 6],
                    [7, 8, 9],
                    [5, 6, 7],
                ],
                [
                    [4, 9, 3],
                    [12, 11, 4],
                    [9, 6, 5],
                ],
                [
                    [-4, 9, 3],
                    [12, 11, 4],
                    [2, 6, 7],
                ],
                [
                    [4, -9, 3],
                    [12, 11, 4],
                    [5, 3, 7],
                ],
                [
                    [4, 9, -3],
                    [12, 11, 4],
                    [1, 6, 7],
                ],
                [
                    [4, 9, 3],
                    [-12, 11, 4],
                    [6, 6, 0],
                ],
                [
                    [4, 9, 3],
                    [12, -11, 4],
                    [5, 6, 7],
                ],
                [
                    [4, 9, 3],
                    [12, 11, -4],
                    [1, 2, -7],
                ],
            ];
        }

        public static function dataProviderForCrossProduct(): array
        {
            return [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                [
                    [1, 2, 3],
                    [4, -5, 6],
                ],
                [
                    [-1, 2, -3],
                    [4, -5, 6],
                ],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                [
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [
                    [4, 9, 3],
                    [12, 11, 4],
                ],
                [
                    [-4, 9, 3],
                    [12, 11, 4],
                ],
                [
                    [4, -9, 3],
                    [12, 11, 4],
                ],
                [
                    [4, 9, -3],
                    [12, 11, 4],
                ],
                [
                    [4, 9, 3],
                    [-12, 11, 4],
                ],
                [
                    [4, 9, 3],
                    [12, -11, 4],
                ],
                [
                    [4, 9, 3],
                    [12, 11, -4],
                ],
            ];
        }

        public static function dataProviderForOuterProduct(): array
        {
            return [
                [
                    [2],
                    [6],
                ],
                [
                    [2, 5, 8],
                    [6, 4, 9],
                ],
                [
                    [3, 6, 3, 5, 8, 21],
                    [12, 4, 5, 3, 21, 4],
                ],
            ];
        }

        public static function dataProviderForAdditiveInverse(): array
        {
            return [
                [
                    [2],
                    [-2],
                ],
                [
                    [0, 1, 2, 3, 4, 5, -6, -7, 8],
                    [0, -1, -2, -3, -4, -5, 6, 7, -8],
                ],
            ];
        }

        public static function dataProviderForPerpendicularIdentity(): array
        {
            return [
                [[0, 0]],
                [[0, 1]],
                [[1, 0]],
                [[1, 1]],
                [[1, 2]],
                [[1, 2]],
                [[1, 3]],
                [[1, 4]],
                [[2, 0]],
                [[2, 1]],
                [[2, 2]],
                [[2, 3]],
                [[2, 4]],
                [[4, 7]],
                [[5, 3]],
                [[-2, 1]],
                [[2, -1]],
                [[-2, -1]],
                [[6, 9]],
                [[-9, 12]],
            ];
        }

        public static function dataProviderForProjPerp(): array
        {
            return [
                [
                    [1, 2],
                    [2, 3],
                ],
                [
                    [2, 2],
                    [2, 7],
                ],
                [
                    [1, 1],
                    [2, 2],
                ],
                [
                    [2, 2],
                    [1, 1],
                ],
                [
                    [5, 9],
                    [12, 8],
                ],
                [
                    [5, 2],
                    [3, 3],
                ],
            ];
        }

        /**
         * @test         Axiom: |x|₂ ≤ |x|₁ ≤ √n |x|₂
         * l²-norm is less than equal to l₁-norm which is less than equal to sqrt n * l²-norm.
         *
         * @dataProvider dataProviderForSingleVector
         */
        public function testL2NormLessThanL1NormLessThanSqrtNL2Norm(array $V)
        {
            // Given
            try
            {
                $V = new Vector($V);
            } catch (BadDataException $e)
            {
            }
            $n = $V->getN();

            // When
            $l₁norm = $V->l1Norm();
            $l²norm = $V->l2Norm();
            $√nl²norm = $n * $l²norm;

            // Then
            $this->assertLessThanOrEqual($l₁norm, $l²norm);
            $this->assertLessThanOrEqual($√nl²norm, $l₁norm);
            $this->assertLessThanOrEqual($√nl²norm, $l²norm);
        }

        /**
         * @test         Axiom: |x|∞ ≤ |x|₂ ≤ √n |x|∞
         * Max norm is less than equal to l₂-norm which is less than equal to sqrt n * max norm.
         *
         * @dataProvider dataProviderForSingleVector
         */
        public function testMaxNormLessThtanEQualL2NormLessThanEqualSqrtNMaxNorm(
            array $V
        ) {
            // Given
            try
            {
                $V = new Vector($V);
            } catch (BadDataException $e)
            {
            }
            $n = $V->getN();

            // When
            $max_norm = $V->maxNorm();
            $l²norm = $V->l2Norm();
            $√n_max_norm = $n * $max_norm;

            // Then
            $this->assertLessThanOrEqual($l²norm, $max_norm);
            $this->assertLessThanOrEqual($√n_max_norm, $l²norm);
            $this->assertLessThanOrEqual($√n_max_norm, $max_norm);
        }

        /**
         * @test         Axiom: |x|∞ ≤ |x|₁ ≤ √n |x|∞
         * Max norm is less than equal to l₁-norm which is less than equal to sqrt n * max norm.
         *
         * @dataProvider dataProviderForSingleVector
         */
        public function testMaxNormLessThanEqualL1NormLessThanEqualSqrtNMaxNorm(
            array $V
        ) {
            // Given
            try
            {
                $V = new Vector($V);
            } catch (BadDataException $e)
            {
            }
            $n = $V->getN();

            // When
            $max_norm = $V->maxNorm();
            $l₁norm = $V->l1Norm();
            $√n_max_norm = $n * $max_norm;

            // Then
            $this->assertLessThanOrEqual($l₁norm, $max_norm);
            $this->assertLessThanOrEqual($√n_max_norm, $l₁norm);
            $this->assertLessThanOrEqual($√n_max_norm, $max_norm);
        }

        /**
         * @test         Axiom: A⋅B = B⋅A
         * Dot product is commutative
         * @dataProvider dataProviderForTwoVectors
         */
        public function testDotProductCommutative(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $A⋅B = $A->dotProduct($B);
            } catch (VectorException $e)
            {
            }
            try
            {
                $B⋅A = $B->dotProduct($A);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($A⋅B, $B⋅A);
        }

        /**
         * @test         Axiom: 0⋅A = A⋅0 = 0
         * Dot product of a vector and zero is zero.
         * @dataProvider dataProviderForDotProductZero
         */
        public function testDotProductZero(array $A, array $zero)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $zero = new Vector($zero);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $A⋅zero = $A->dotProduct($zero);
            } catch (VectorException $e)
            {
            }
            try
            {
                $zero⋅A = $zero->dotProduct($A);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals(0, $A⋅zero);
            $this->assertEquals(0, $zero⋅A);
            $this->assertEquals($A⋅zero, $zero⋅A);
        }

        /**
         * @test         Axiom: A x B = -(B x A)
         * Anticommutivity: Reverse order cross product results in a negative cross product
         * @dataProvider dataProviderForCrossProduct
         */
        public function testReverseCrossProduct(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $AxB = $A->crossProduct($B);
            } catch (VectorException $e)
            {
            }
            try
            {
                $BxA = $B->crossProduct($A);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($AxB[0], -$BxA[0]);
            $this->assertEquals($AxB[1], -$BxA[1]);
            $this->assertEquals($AxB[2], -$BxA[2]);
        }

        /**
         * @test         Axiom: A x 0 = 0
         * Cross product property of 0
         * @dataProvider dataProviderForCrossProduct
         */
        public function testCrossProductPropertyOfZero(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $zero = new Vector(array_fill(0, $A->getN(), 0));
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $Ax0 = $A->crossProduct($zero);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($zero, $Ax0);
            $this->assertEquals($zero->getVector(), $Ax0->getVector());
        }

        /**
         * @test         Axiom: A x (B + C) = (A x B) + (A x C)
         * Cross product distributivity
         * @dataProvider dataProviderForCrossProductThreeVectors
         */
        public function testCrossProductDistributivity(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $C = new Vector($C);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $Ax⟮B＋C⟯ = $A->crossProduct($B->add($C));
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮AxB⟯＋⟮AxC⟯ = $A->crossProduct($B)->add($A->crossProduct($C));
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($Ax⟮B＋C⟯, $⟮AxB⟯＋⟮AxC⟯);
            $this->assertEquals($Ax⟮B＋C⟯->getVector(),
                $⟮AxB⟯＋⟮AxC⟯->getVector());
        }

        /**
         * @test         Axiom: (A x B) ⋅ A = 0
         * @test         Axiom: (A x B) ⋅ B = 0
         * Dot product of either vector with the cross product is always zero.
         * @dataProvider dataProviderForCrossProduct
         */
        public function testCrossProductInnerProductWithEitherVectorIsZero(
            array $A,
            array $B
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $AxB = $A->crossProduct($B);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals(0, $AxB->innerProduct(B: $A));
            $this->assertEquals(0, $AxB->innerProduct(B: $B));
        }

        /**
         * @test         Axiom: A ⋅ (B x C) = (A x B) ⋅ C
         * Cross product volumn property
         * @dataProvider dataProviderForCrossProductThreeVectors
         */
        public function testCrossProductVolumeProperty(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $C = new Vector($C);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $A⋅⟮BxC⟯ = $A->dotProduct($B->crossProduct($C));
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮AxB⟯⋅C = $A->crossProduct($B)->dotProduct($C);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($A⋅⟮BxC⟯, $⟮AxB⟯⋅C);
        }

        /**
         * @test         Axiom: A x (B x C) = (A ⋅ C)B - (A ⋅ B)C
         * Lagrange's formula
         * @dataProvider dataProviderForCrossProductThreeVectors
         */
        public function testCrossProductLagrangeFormula(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $C = new Vector($C);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $Ax⟮BxC⟯ = $A->crossProduct($B->crossProduct($C));
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮A⋅C⟯B = $B->scalarMultiply($A->dotProduct($C));
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮A⋅B⟯C = $C->scalarMultiply($A->dotProduct($B));
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮A⋅C⟯B−⟮A⋅B⟯C = $⟮A⋅C⟯B->subtract($⟮A⋅B⟯C);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($Ax⟮BxC⟯, $⟮A⋅C⟯B−⟮A⋅B⟯C);
            $this->assertEquals($Ax⟮BxC⟯->getVector(),
                $⟮A⋅C⟯B−⟮A⋅B⟯C->getVector());
        }

        /**
         * @test         Axiom: A⨂B = ABᵀ
         * Outer product is the same as matrix multiplication of A and transpose of B
         * @dataProvider dataProviderForOuterProduct
         */
        public function testOuterProductIsMatrixMultiplicationOfAAndBTranspose(
            array $A,
            array $B
        ) {
            // Given Vector A⨂B
            try
            {
                $Av = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $Bv = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            $A⨂B = $Av->outerProduct(B: $Bv);

            // When Matrix multiplication ABᵀ
            try
            {
                $Am = $Av->asColumnMatrix();
            } catch (MathException $e)
            {
            }
            try
            {
                $Bᵀ = new NumericMatrix([
                    $Bv->getVector(),
                ]);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $ABᵀ = $Am->multiply($Bᵀ);
            } catch (IncorrectTypeException $e)
            {
            } catch (MatrixException $e)
            {
            } catch (MathException $e)
            {
            }

            // Then
            $this->assertEquals($A⨂B, $ABᵀ);
        }

        /**
         * @test         Axiom: A⨂B = AB (direct product)
         * Outer product is the same as the direct product
         * @dataProvider dataProviderForTwoVectors
         */
        public function testOuterProductIsDirectProduct(array $A, array $B)
        {
            // Given Outer product
            try
            {
                $Av = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $Bv = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            $A⨂B = $Av->outerProduct(B: $Bv);

            // When Direct product
            $AB = $Av->directProduct(B: $Bv);

            // Then
            $this->assertEquals($A⨂B->getMatrix(), $AB->getMatrix());
        }

        /**
         * @test         Axiom: (c + d)A = cA + dA
         * Additivity in the scalar
         * @dataProvider dataProviderForSingleVector
         */
        public function testAdditivityInTheScalarForScalarMultiplication(
            array $A
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            $c = 2;
            $d = 9;

            // When
            $⟮c＋d⟯A = $A->scalarMultiply(k: $c + $d);
            try
            {
                $⟮cA＋dA⟯ = $A->scalarMultiply($c)->add($A->scalarMultiply($d));
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($⟮c＋d⟯A, $⟮cA＋dA⟯);
            $this->assertEquals($⟮c＋d⟯A->getVector(), $⟮cA＋dA⟯->getVector());
        }

        /**
         * @test         Axiom: c(A + B) = cA + cB
         * Additivity in the vector
         * @dataProvider dataProviderForTwoVectors
         */
        public function testAdditivityInTheVectorForScalarMultiplication(
            array $A,
            array $B
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }
            $c = 4;

            // When
            try
            {
                $c⟮A＋B⟯ = $A->add($B)->scalarMultiply($c);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }
            try
            {
                $⟮cA＋cB⟯ = $A->scalarMultiply($c)->add($B->scalarMultiply($c));
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($c⟮A＋B⟯, $⟮cA＋cB⟯);
            $this->assertEquals($c⟮A＋B⟯->getVector(), $⟮cA＋cB⟯->getVector());
        }

        /**
         * @test         Axiom: 1A = A
         * Multiplying (scaling) by 1 does not change the vector
         * @dataProvider dataProviderForSingleVector
         */
        public function testScalarMultiplyOneIdentity(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }

            // When
            $１A = $A->scalarMultiply(k: 1);

            // Then
            $this->assertEquals($A, $１A);
            $this->assertEquals($A->getVector(), $１A->getVector());
        }

        /**
         * @test         Axiom: 0A = 0
         * Multiplying (scaling) by 0 gives the zero vector
         * @dataProvider dataProviderForSingleVector
         */
        public function testScalarMultiplyZeroIdentity(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }

            // When
            $０A = $A->scalarMultiply(k: 0);
            try
            {
                $zero = new Vector(array_fill(0, $A->getN(), 0));
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->assertEquals($zero, $０A);
            $this->assertEquals($zero->getVector(), $０A->getVector());
        }

        /**
         * @test         Axiom: -1A = -A
         * Additive inverse
         * @dataProvider dataProviderForAdditiveInverse
         */
        public function testScalarMultiplyNegativeOneIdentity(
            array $A,
            array $R
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }

            // When
            $ーA = $A->scalarMultiply(k: -1);
            try
            {
                $R = new Vector($R);
            } catch (BadDataException $e)
            {
            }

            // Then
            $this->assertEquals($R, $ーA);
            $this->assertEquals($R->getVector(), $ーA->getVector());
        }

        /**
         * @test         Axiom: A⋅A⊥ = 0
         * Vector dot product with a vector perpendicular to it will be zero.
         * @dataProvider dataProviderForPerpendicularIdentity
         */
        public function testPerpendicularDotProduct(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $A⊥ = $A->perpendicular();
            } catch (VectorException $e)
            {
            }
            try
            {
                $A⋅A⊥ = $A->dotProduct($A⊥);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals(0, $A⋅A⊥);
        }

        /**
         * @test         Axiom: A⊥⋅A = 0
         * Perp dot product with itself will be zero.
         * @dataProvider dataProviderForPerpendicularIdentity
         */
        public function testPerpDotProductZero(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }

            // When
            try
            {
                $A⊥⋅A = $A->perpDotProduct($A);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals(0, $A⊥⋅A);
        }

        /**
         * @test         Axiom: A⋅A⊥ = -A⊥⋅A
         * Swapping operands changes the sign of the perp dot product
         * @dataProvider dataProviderForPerpendicularIdentity
         */
        public function testPerpDotProdcutSwapOperandsChangeSign(array $A)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $A⊥ = $A->perpendicular();
            } catch (VectorException $e)
            {
            }

            // When
            try
            {
                $A⋅A⊥ = $A->dotProduct($A⊥);
            } catch (VectorException $e)
            {
            }
            try
            {
                $A⊥⋅A = $A⊥->dotProduct($A);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEquals($A⋅A⊥, -$A⊥⋅A);
        }

        /**
         * @test         Axiom: projᵇA + perpᵇA = A
         * Sum of the proj and perp of A on B equals A
         * @dataProvider dataProviderForProjPerp
         */
        public function testProjPerpSumEqualsA(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            $projᵇA = $A->projection(B: $B);
            $perpᵇA = $A->perp(B: $B);

            try
            {
                $projᵇA＋perpᵇA = $projᵇA->add($perpᵇA);
            } catch (BadDataException $e)
            {
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($A, $projᵇA＋perpᵇA, 0.00001);
            $this->assertEqualsWithDelta($A->getVector(),
                $projᵇA＋perpᵇA->getVector(), 0.00001);
        }

        /**
         * @test         Axiom: |projᵇA|² + |perpᵇA|² = |A|²
         * Sum of squared lengths of proj and perp equals squared length of A
         * @dataProvider dataProviderForProjPerp
         */
        public function testProjPerpSumOfSquares(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            $│A│² = ($A->length()) ** 2;
            $│projᵇA│² = ($A->projection(B: $B)->length()) ** 2;
            $│perpᵇA│² = ($A->perp(B: $B)->length()) ** 2;

            // Then
            $this->assertEqualsWithDelta($│A│², $│projᵇA│² + $│perpᵇA│²,
                0.00001);
        }

        /**
         * @test         Axiom: projᵇA ⋅ perpᵇA = 0
         * Dot product of proj and perp of A on B is 0
         * @dataProvider dataProviderForProjPerp
         */
        public function testProjPerpDotProductEqualsZero(array $A, array $B)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            $projᵇA = $A->projection(B: $B);
            $perpᵇA = $A->perp(B: $B);

            try
            {
                $projᵇA⋅perpᵇA = $projᵇA->dotProduct($perpᵇA);
            } catch (VectorException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta(0, $projᵇA⋅perpᵇA, 0.00001);
        }

        /**
         * @test         Axiom: |projᵇA⊥ ⋅ perpᵇA| = |projᵇA| |perpᵇA|
         * Absolute value of proj and perp dot product equals product of their lengths.
         * @dataProvider dataProviderForProjPerp
         */
        public function testProjPerpPerpDotProductEqualsProductOfLengths(
            array $A,
            array $B
        ) {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (BadDataException $e)
            {
            }
            try
            {
                $B = new Vector($B);
            } catch (BadDataException $e)
            {
            }

            // When
            $projᵇA = $A->projection(B: $B);
            try
            {
                $projᵇA⊥ = $A->projection($B)->perpendicular();
            } catch (VectorException $e)
            {
            }
            $perpᵇA = $A->perp(B: $B);

            try
            {
                $projᵇA⊥⋅perpᵇA = abs($projᵇA⊥->dotProduct($perpᵇA));
            } catch (VectorException $e)
            {
            }
            $│projᵇA│ = $projᵇA->length();
            $│perpᵇA│ = $perpᵇA->length();

            // Then
            $this->assertEqualsWithDelta($projᵇA⊥⋅perpᵇA, $│projᵇA│ * $│perpᵇA│,
                0.00001);
        }
    }
