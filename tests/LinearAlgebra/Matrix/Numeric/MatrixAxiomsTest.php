<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Numeric;

    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericSquareMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use MathPHP\NumberTheory\Integer;
    use MathPHP\Tests;
    use PHPUnit\Framework\TestCase;

    use function abs;
    use function array_column;
    use function array_fill;
    use function is_nan;
    use function min;
    use function range;
    use function round;

    /**
     * Tests of Matrix axioms
     * These tests don't test specific functions,
     * but rather matrix axioms which in term make use of multiple functions.
     * If all the Matrix math is implemented properly, these tests should
     * all work out according to the axioms.
     *
     * Axioms tested:
     *  - Addition
     *    - r(A + B) = rA + rB
     *    - A + (−A) = 0
     *  - Multiplication
     *    - (AB)C = A(BC)
     *    - A(B + C) = AB + BC
     *    - r(AB) = (rA)B = A(rB)
     *  - Identity
     *    - AI = A = IA
     *    - I is involutory
     *  - Inverse
     *    - AA⁻¹ = I = A⁻¹A
     *    - (A⁻¹)⁻¹ = A
     *    - (AB)⁻¹ = B⁻¹A⁻¹
     *    - A is invertible, Aᵀ is inveritble
     *    - A is invertible, AAᵀ is inveritble
     *    - A is invertible, AᵀA is inveritble
     *  - Transpose
     *    - (Aᵀ)ᵀ = A
     *    - (A⁻¹)ᵀ = (Aᵀ)⁻¹
     *    - (rA)ᵀ = rAᵀ
     *    - (AB)ᵀ = BᵀAᵀ
     *    - (A + B)ᵀ = Aᵀ + Bᵀ
     *  - Trace
     *    - tr(A) = tr(Aᵀ)
     *    - tr(AB) = tr(BA)
     *  - Determinant
     *    - det(A) = det(Aᵀ)
     *    - det(AB) = det(A)det(B)
     *  - LU Decomposition (PA = LU)
     *    - PA = LU
     *    - A = P⁻¹LU
     *    - PPᵀ = I = PᵀP
     *    - (PA)⁻¹ = (LU)⁻¹ = U⁻¹L⁻¹
     *    - P⁻¹ = Pᵀ
     *  - QR Decomposition (A = QR)
     *    - A = QR
     *    - Q is orthogonal, R is upper triangular
     *    - QᵀQ = I
     *    - R = QᵀA
     *    - Qᵀ = Q⁻¹
     *  - Crout Decomposition (A = LU)
     *    - A = LU where L = LD
     *  - Cholesky Decomposition (A = LLᵀ)
     *     - A = LLᵀ
     *  - System of linear equations (Ax = b)
     *    - Ax - b = 0
     *    - x = A⁻¹b
     *    - LU: Ly = Pb and Ux = y
     *    - QR: x = R⁻¹Qᵀb
     *    - RREF of A augmented with B provides a solution to Ax = b
     *  - Symmetric matrix
     *    - A is square
     *    - A = Aᵀ
     *    - A⁻¹Aᵀ = I
     *    - A + B is symmetric
     *    - A - B is symmetric
     *    - kA is symmetric
     *    - AAᵀ is symmetric
     *    - AᵀA is symmetric
     *    - A is invertible symmetric, A⁻¹ is symmetric
     *  - Skew-symmetric matrix
     *    - The sum of two skew-symmetric matrices is skew-symmetric
     *    - Scalar multiple of a skew-symmetric matrix is skew-symmetric
     *    - The elements on the diagonal of a skew-symmetric matrix are zero, and therefore also its trace
     *    - A is skew-symmetric, then det(A) ≥ 0
     *    - A is a real skew-symmetric matrix, then I+A is invertible, where I is the identity matrix
     *  - Kronecker product
     *    - A ⊗ (B + C) = A ⊗ B + A ⊗ C
     *    - (A + B) ⊗ C = A ⊗ C + B ⊗ C
     *    - (A ⊗ B) ⊗ C = A ⊗ (B ⊗ C)
     *    - (A ⊗ B)(C ⊗ D) = AC ⊗ BD
     *    - (kA) ⊗ B = A ⊗ (kB) = k(A ⊗ B)
     *    - (A ⊗ B)⁻¹ = A⁻¹ ⊗ B⁻¹
     *    - (A ⊗ B)ᵀ = Aᵀ ⊗ Bᵀ
     *    - det(A ⊗ B) = det(A)ᵐ det(B)ⁿ
     *  - Kronecker sum
     *    - A⊕B = A⊗Ib + I⊗aB
     *  - Covariance matrix
     *    - S = Sᵀ
     *  - Positive definiteness
     *    - A is PD ⇔ -A is ND
     *    - A is PSD ⇔ -A is NSD
     *    - A is PD ⇒ A is PSD
     *    - A is ND ⇒ A is NSD
     *    - A is PD ⇔ A⁻¹ is PD
     *    - A is ND ⇔ A⁻¹ is ND
     *    - A is PD and r > 0 ⇒ rA is PD
     *    - A and B are PD ⇒ A + B is PD
     *    - A and B are PD ⇒ ABA is PD
     *    - A and B are PD ⇒ BAB is PD
     *  - Triangular
     *    - Zero matrix is lower triangular
     *    - Zero matrix is upper triangular
     *    - Lᵀ is upper triangular
     *    - Uᵀ is lower triangular
     *    - LL is lower triangular
     *    - UU is upper triangular
     *    - L + L is lower triangular
     *    - U + U is upper triangular
     *    - L⁻¹ is lower triangular (If L is invertible)
     *    - U⁻¹ is upper triangular (If U is invertible)
     *    - kL is lower triangular
     *    - kU is upper triangular
     *    - L is invertible iif diagonal is all non zero
     *    - U is invertible iif diagonal is all non zero
     *  - Diagonal
     *    - Zero matrix is diagonal
     *    - Dᵀ is diagonal
     *    - DD is diagonal
     *    - D + D is diagonal
     *    - D⁻¹ is diagonal (If D is invertible)
     *    - kD is lower triangular
     *    - D is invertible iif diagonal is all non zero
     *  - Reduced row echelon form
     *    - RREF is upper triangular
     *  - Exchange matrix
     *    - Jᵀ = J
     *    - J⁻¹ = J
     *    - tr(J) is 1 if n is odd, and 0 if n is even
     *  - Signature matrix
     *    - A is involutory
     *  - Hilbert matrix
     *    - H is symmetric
     *    - H is positive definite
     *  - Cholesky decomposition
     *    - A = LLᵀ
     *    - L is lower triangular
     *    - Lᵀ is upper triangular
     *  - Adjugate matrix
     *    - adj⟮A⟯ = Cᵀ
     *    - A adj⟮A⟯ = det⟮A⟯ I
     *    - A⁻¹ = (1/det⟮A⟯) adj⟮A⟯
     *    - adj⟮I⟯ = I
     *    - adj⟮AB⟯ = adj⟮B⟯adj⟮A⟯
     *    - adj⟮cA⟯ = cⁿ⁻¹ adj⟮A⟯
     *    - adj⟮B⟯adj⟮A⟯ = det⟮B⟯B⁻¹ det⟮A⟯A⁻¹ = det⟮AB⟯⟮AB⟯⁻¹
     *    - adj⟮Aᵀ⟯ = adj⟮A⟯ᵀ
     *    - Aadj⟮A⟯ = adj⟮A⟯A = det⟮A⟯I
     *  - Rank
     *    - rank(A) ≤ min(m, n)
     *    - Zero matrix has rank of 0
     *    - If A is square matrix, then it is invertible only if rank = n (full rank)
     *    - rank(AᵀA) = rank(AAᵀ) = rank(A) = rank(Aᵀ)
     *  - Bi/tridiagonal - Hessenberg
     *    - Lower bidiagonal matrix is upper Hessenberg
     *    - Upper bidiagonal matrix is lower Hessenberg
     *    - A matrix that is both upper Hessenberg and lower Hessenberg is a tridiagonal matrix
     *  - Orthogonal matrix
     *    - AAᵀ = I
     *    - AᵀA = I
     *    - A⁻¹ = Aᵀ
     *    - det(A) = 1
     *  - Householder matrix transformation
     *    - H is involutory
     *    - H has determinant that is -1
     *    - H has eigenvalues 1 and -1
     *  - Nilpotent matrix
     *    - tr(Aᵏ) = 0 for all k > 0
     *    - det(A) = 0
     *    - Cannot be invertible
     */
    class MatrixAxiomsTest extends TestCase {
        use Tests\LinearAlgebra\Fixture\MatrixDataProvider;

        public static function dataProviderForScalarMultiplicationOrderAddition(
        ): array
        {
            return [
                [
                    [
                        [1, 5],
                        [4, 3],
                    ],
                    [
                        [5, 6],
                        [2, 1],
                    ],
                    5,
                ],
                [
                    [
                        [3, 8, 5],
                        [3, 6, 1],
                        [9, 5, 8],
                    ],
                    [
                        [5, 3, 8],
                        [6, 4, 5],
                        [1, 8, 9],
                    ],
                    4,
                ],
                [
                    [
                        [-4, -2, 9],
                        [3, 14, -6],
                        [3, 9, 9],
                    ],
                    [
                        [8, 7, 8],
                        [-5, 4, 1],
                        [3, 5, 1],
                    ],
                    7,
                ],
                [
                    [
                        [4, 7, 7, 8],
                        [3, 6, 4, 1],
                        [-3, 6, 8, -3],
                        [3, 2, 1, -54],
                    ],
                    [
                        [3, 2, 6, 7],
                        [4, 3, -6, 2],
                        [12, 14, 14, -6],
                        [4, 6, 4, -42],
                    ],
                    -8,
                ],
            ];
        }

        /**
         * @return array [A, Z]
         */
        public static function dataProviderForNegateAdditionZeroMatrix(): array
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
                        [0],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    [
                        [0, 0, 0],
                        [0, 0, 0],
                        [0, 0, 0],
                    ],
                ],
                [
                    [
                        [5, -4, 3, 2, -10],
                        [5, 5, 5, -4, -4],
                        [0, 0, -2, 4, 49],
                        [4, 3, 0, 0, -1],
                    ],
                    [
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                        [0, 0, 0, 0, 0],
                    ],
                ],
            ];
        }

        public static function dataProviderForMultiplicationIsAssociative(
        ): array
        {
            return [
                [
                    [
                        [1, 5, 3],
                        [3, 6, 3],
                        [6, 7, 8],
                    ],
                    [
                        [6, 9, 9],
                        [3, 5, 1],
                        [3, 5, 12],
                    ],
                    [
                        [7, 4, 6],
                        [2, 3, 1],
                        [10, 12, 5],
                    ],
                ],
                [
                    [
                        [12, 21, 6],
                        [-3, 11, -6],
                        [3, 6, -3],
                    ],
                    [
                        [3, 7, 8],
                        [4, 4, 2],
                        [6, -4, 1],
                    ],
                    [
                        [1, -1, -5],
                        [6, 5, 19],
                        [3, 6, -2],
                    ],
                ],
            ];
        }

        public static function dataProviderForMultiplicationIsDistributive(
        ): array
        {
            return [
                [
                    [
                        [1, 2],
                        [0, -1],
                    ],
                    [
                        [0, -1],
                        [1, 1],
                    ],
                    [
                        [-2, 0],
                        [0, 1],
                    ],
                ],
                [
                    [
                        [1, 5, 3],
                        [3, 6, 3],
                        [6, 7, 8],
                    ],
                    [
                        [6, 9, 9],
                        [3, 5, 1],
                        [3, 5, 12],
                    ],
                    [
                        [7, 4, 6],
                        [2, 3, 1],
                        [10, 12, 5],
                    ],
                ],
                [
                    [
                        [12, 21, 6],
                        [-3, 11, -6],
                        [3, 6, -3],
                    ],
                    [
                        [3, 7, 8],
                        [4, 4, 2],
                        [6, -4, 1],
                    ],
                    [
                        [1, -1, -5],
                        [6, 5, 19],
                        [3, 6, -2],
                    ],
                ],
            ];
        }

        public static function dataProviderForScalarMultiplicationOrder(): array
        {
            return [
                [
                    [
                        [1, 5],
                        [4, 3],
                    ],
                    [
                        [5, 6],
                        [2, 1],
                    ],
                    5,
                ],
                [
                    [
                        [3, 8, 5],
                        [3, 6, 1],
                        [9, 5, 8],
                    ],
                    [
                        [5, 3, 8],
                        [6, 4, 5],
                        [1, 8, 9],
                    ],
                    4,
                ],
                [
                    [
                        [-4, -2, 9],
                        [3, 14, -6],
                        [3, 9, 9],
                    ],
                    [
                        [8, 7, 8],
                        [-5, 4, 1],
                        [3, 5, 1],
                    ],
                    7,
                ],
                [
                    [
                        [4, 7, 7, 8],
                        [3, 6, 4, 1],
                        [-3, 6, 8, -3],
                        [3, 2, 1, -54],
                    ],
                    [
                        [3, 2, 6, 7],
                        [4, 3, -6, 2],
                        [12, 14, 14, -6],
                        [4, 6, 4, -42],
                    ],
                    -8,
                ],
            ];
        }

        public static function dataProviderForInverse(): array
        {
            return [
                [
                    [
                        [4, 7],
                        [2, 6],
                    ],
                    [
                        [0.6, -0.7],
                        [-0.2, 0.4],
                    ],
                ],
                [
                    [
                        [4, 3],
                        [3, 2],
                    ],
                    [
                        [-2, 3],
                        [3, -4],
                    ],
                ],
                [
                    [
                        [1, 2],
                        [3, 4],
                    ],
                    [
                        [-2, 1],
                        [3 / 2, -1 / 2],
                    ],
                ],
                [
                    [
                        [3, 3.5],
                        [3.2, 3.6],
                    ],
                    [
                        [-9, 8.75],
                        [8, -7.5],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [0, 4, 5],
                        [1, 0, 6],
                    ],
                    [
                        [12 / 11, -6 / 11, -1 / 11],
                        [5 / 22, 3 / 22, -5 / 22],
                        [-2 / 11, 1 / 11, 2 / 11],
                    ],
                ],
                [
                    [
                        [7, 2, 1],
                        [0, 3, -1],
                        [-3, 4, -2],
                    ],
                    [
                        [-2, 8, -5],
                        [3, -11, 7],
                        [9, -34, 21],
                    ],
                ],
                [
                    [
                        [3, 6, 6, 8],
                        [4, 5, 3, 2],
                        [2, 2, 2, 3],
                        [6, 8, 4, 2],
                    ],
                    [
                        [-0.333, 0.667, 0.667, -0.333],
                        [0.167, -2.333, 0.167, 1.417],
                        [0.167, 4.667, -1.833, -2.583],
                        [0.000, -2.000, 1.000, 1.000],
                    ],
                ],
                [
                    [
                        [4, 23, 6, 4, 7],
                        [3, 64, 23, 52, 2],
                        [65, 45, 3, 23, 1],
                        [2, 3, 4, 3, 9],
                        [53, 99, 54, 32, 105],
                    ],
                    [
                        [-0.142, 0.006, 0.003, -0.338, 0.038],
                        [0.172, -0.012, 0.010, 0.275, -0.035],
                        [-0.856, 0.082, -0.089, -2.344, 0.257],
                        [0.164, -0.001, 0.026, 0.683, -0.070],
                        [0.300, -0.033, 0.027, 0.909, -0.088],
                    ],
                ],
            ];
        }

        public static function dataProviderForInverseProductIsReverseProductOfInverses(
        ): array
        {
            return [
                [
                    [
                        [1, 5],
                        [4, 3],
                    ],
                    [
                        [5, 6],
                        [2, 1],
                    ],
                ],
                [
                    [
                        [3, 8, 5],
                        [3, 6, 1],
                        [9, 5, 8],
                    ],
                    [
                        [5, 3, 8],
                        [6, 4, 5],
                        [1, 8, 9],
                    ],
                ],
                [
                    [
                        [-4, -2, 9],
                        [3, 14, -6],
                        [3, 9, 9],
                    ],
                    [
                        [8, 7, 8],
                        [-5, 4, 1],
                        [3, 5, 1],
                    ],
                ],
                [
                    [
                        [4, 7, 7, 8],
                        [3, 6, 4, 1],
                        [-3, 6, 8, -3],
                        [3, 2, 1, -54],
                    ],
                    [
                        [3, 2, 6, 7],
                        [4, 3, -6, 2],
                        [12, 14, 14, -6],
                        [4, 6, 4, -42],
                    ],
                ],
            ];
        }

        public static function dataProviderForKroneckerProductDeterminant(
        ): array
        {
            return [
                [
                    [
                        [5],
                    ],
                    [
                        [4],
                    ],
                ],
                [
                    [
                        [5, 6],
                        [2, 4],
                    ],
                    [
                        [4, 9],
                        [3, 1],
                    ],
                ],
                [
                    [
                        [5, 6],
                        [-2, 4],
                    ],
                    [
                        [4, -9],
                        [3, 1],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [2, 4, 6],
                        [7, 6, 5],
                    ],
                    [
                        [2, 3, 4],
                        [3, 1, 6],
                        [4, 3, 3],
                    ],
                ],
                [
                    [
                        [1, -2, 3],
                        [2, -4, 6],
                        [7, -6, 5],
                    ],
                    [
                        [2, 3, 4],
                        [3, 1, 6],
                        [4, 3, 3],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4],
                        [2, 4, 6, 8],
                        [7, 6, 5, 4],
                        [1, 3, 5, 7],
                    ],
                    [
                        [2, 3, 4, 5],
                        [3, 1, 6, 3],
                        [4, 3, 3, 4],
                        [3, 3, 4, 1],
                    ],
                ],
                [
                    [
                        [-1, 2, 3, 4],
                        [2, 4, 6, 8],
                        [7, 6, 5, 4],
                        [1, 3, 5, 7],
                    ],
                    [
                        [2, 3, 4, 5],
                        [3, 1, 6, 3],
                        [4, 3, 3, -4],
                        [3, 3, 4, 1],
                    ],
                ],
                [
                    [
                        [1, 2, 3, 4, 5],
                        [2, 4, 6, 8, 10],
                        [7, 6, 5, 4, 5],
                        [1, 3, 5, 7, 9],
                        [1, 2, 3, 4, 5],
                    ],
                    [
                        [2, 3, 4, 5, 2],
                        [3, 1, 6, 3, 2],
                        [4, 3, 3, 4, 2],
                        [3, 3, 4, 1, 2],
                        [1, 1, 2, 2, 1],
                    ],
                ],
            ];
        }

        public static function dataProviderForCovarianceSymmetric(): array
        {
            return [
                [
                    [
                        [1, 4, 7, 8],
                        [2, 2, 8, 4],
                        [1, 13, 1, 5],
                    ],
                ],
                [
                    [
                        [19, 22, 6, 3, 2, 20],
                        [12, 6, 9, 15, 13, 5],
                    ],
                ],
                [
                    [
                        [4, 4.2, 3.9, 4.3, 4.1],
                        [2, 2.1, 2, 2.1, 2.2],
                        [.6, .59, .58, .62, .63],
                    ],
                ],
                [
                    [
                        [2.5, 0.5, 2.2, 1.9, 3.1, 2.3, 2, 1, 1.5, 1.1],
                        [2.4, 0.7, 2.9, 2.2, 3.0, 2.7, 1.6, 1.1, 1.6, 0.9],
                    ],
                ],
            ];
        }

        /**
         * @test         Axiom: r(A + B) = rA + rB
         * Order of scalar multiplication does not matter.
         *
         * @dataProvider dataProviderForScalarMultiplicationOrderAddition
         *
         * @param array $A
         * @param array $B
         * @param int   $r
         *
         * @throws       \Exception
         */
        public function testScalarMultiplicationOrderAddition(
            array $A,
            array $B,
            int $r
        ) {
            // Given
            $A = MatrixFactory::create($A);
            $B = MatrixFactory::create($B);

            // When r(A + B)
            $A＋B = $A->add($B);
            $r⟮A＋B⟯ = $A＋B->scalarMultiply($r);

            // And rA + rB
            $rA = $A->scalarMultiply($r);
            $rB = $B->scalarMultiply($r);
            $rA＋rB = $rA->add($rB);

            // Then
            $this->assertEquals($r⟮A＋B⟯->getMatrix(), $rA＋rB->getMatrix());
        }

        /**
         * @test         Axiom: A + (−A) = 0
         * Adding the negate of a matrix is a zero matrix.
         *
         * @dataProvider dataProviderForNegateAdditionZeroMatrix
         *
         * @param array $A
         * @param array $Z
         *
         * @throws       \Exception
         */
        public function testAddNegateIsZeroMatrix(array $A, array $Z)
        {
            // Given
            $A = MatrixFactory::create($A);
            $Z = MatrixFactory::create($Z);

            // When
            $−A = $A->negate();

            // Then
            $this->assertEquals($Z, $A->add(B: $−A));
            $this->assertEquals($Z, $−A->add(B: $A));
        }

        /**
         * @test         Axiom: (AB)C = A(BC)
         * Matrix multiplication is associative
         *
         * @dataProvider dataProviderForMultiplicationIsAssociative
         *
         * @param array $A
         * @param array $B
         * @param array $C
         *
         * @throws       \Exception
         */
        public function testMultiplicationIsAssociative(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);

            // When
            $⟮AB⟯ = $A->multiply(B: $B);
            $⟮AB⟯C = $⟮AB⟯->multiply(B: $C);

            // And
            $⟮BC⟯ = $B->multiply(B: $C);
            $A⟮BC⟯ = $A->multiply(B: $⟮BC⟯);

            // Then
            $this->assertEquals($⟮AB⟯C->getMatrix(), $A⟮BC⟯->getMatrix());
        }

        /**
         * @test         Axiom: A(B + C) = AB + AC
         * Matrix multiplication is distributive
         *
         * @dataProvider dataProviderForMultiplicationIsDistributive
         *
         * @param array $A
         * @param array $B
         * @param array $C
         *
         * @throws       \Exception
         */
        public function testMultiplicationIsDistributive(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);

            // When A(B + C)
            $⟮B＋C⟯ = $B->add(B: $C);
            $A⟮B＋C⟯ = $A->multiply(B: $⟮B＋C⟯);

            // And AB + AC
            $AB = $A->multiply(B: $B);
            $AC = $A->multiply(B: $C);
            $AB＋AC = $AB->add(B: $AC);

            // Then
            $this->assertEquals($A⟮B＋C⟯->getMatrix(), $AB＋AC->getMatrix());
        }

        /**
         * @test         Axiom: r(AB) = (rA)B = A(rB)
         * Order of scalar multiplication does not matter.
         *
         * @dataProvider dataProviderForScalarMultiplicationOrder
         *
         * @param array $A
         * @param array $B
         * @param int   $r
         *
         * @throws       \Exception
         */
        public function testScalarMultiplcationOrder(array $A, array $B, int $r)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When r(AB)
            $AB = $A->multiply(B: $B);
            $r⟮AB⟯ = $AB->scalarMultiply(λ: $r);

            // And (rA)B
            $rA = $A->scalarMultiply(λ: $r);
            $⟮rA⟯B = $rA->multiply(B: $B);

            // And A(rB)
            $rB = $B->scalarMultiply(λ: $r);
            $A⟮rB⟯ = $A->multiply(B: $rB);

            // Then
            $this->assertEquals($r⟮AB⟯->getMatrix(), $⟮rA⟯B->getMatrix());
            $this->assertEquals($⟮rA⟯B->getMatrix(), $A⟮rB⟯->getMatrix());
            $this->assertEquals($r⟮AB⟯->getMatrix(), $A⟮rB⟯->getMatrix());
        }

        /**
         * @test         Axiom: AI = A = IA
         * Matrix multiplied with the identity matrix is the original matrix.
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testMatrixTimesIdentityIsOriginalMatrix(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $I = MatrixFactory::identity(n: $A->getN());

            // When
            $AI = $A->multiply(B: $I);
            $IA = $I->multiply(B: $A);

            // Then
            $this->assertEquals($A->getMatrix(), $AI->getMatrix());
            $this->assertEquals($A->getMatrix(), $IA->getMatrix());
        }

        /**
         * @test Axiom: I is involutory
         * Identity matrix is involutory
         * @throws \Exception
         */
        public function testIdentityMatrixIsInvolutory()
        {
            // Given
            foreach (range(start: 1, end: 20) as $n)
            {
                // When
                $A = MatrixFactory::identity(n: $n);

                // Then
                $this->assertTrue($A->isInvolutory());
            }
        }

        /**
         * @test         Axiom: AA⁻¹ = I = A⁻¹A
         * Matrix multiplied with its inverse is the identity matrix.
         *
         * @dataProvider dataProviderForInverse
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testMatrixTimesInverseIsIdentity(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();
            $AA⁻¹ = $A->multiply(B: $A⁻¹);
            $A⁻¹A = $A⁻¹->multiply(B: $A);
            $I = MatrixFactory::identity(n: $A->getN());

            // Then
            $this->assertEqualsWithDelta($I->getMatrix(), $AA⁻¹->getMatrix(),
                0.00001);
            $this->assertEqualsWithDelta($I->getMatrix(), $A⁻¹A->getMatrix(),
                0.00001);
            $this->assertEqualsWithDelta($AA⁻¹->getMatrix(), $A⁻¹A->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: (A⁻¹)⁻¹ = A
         * Inverse of inverse is the original matrix.
         *
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testInverseOfInverseIsOriginalMatrix(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $⟮A⁻¹⟯⁻¹ = $A->inverse()->inverse();

            // Then
            $this->assertEqualsWithDelta($A->getMatrix(), $⟮A⁻¹⟯⁻¹->getMatrix(),
                0.00001);
        }

        /**
         * (AB)⁻¹ = B⁻¹A⁻¹
         * The inverse of a product is the reverse product of the inverses.
         *
         * @dataProvider dataProviderForInverseProductIsReverseProductOfInverses
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testInverseProductIsReverseProductOfInverses(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $⟮AB⟯⁻¹ = $A->multiply(B: $B)->inverse();

            // And
            $B⁻¹ = $B->inverse();
            $A⁻¹ = $A->inverse();
            $B⁻¹A⁻¹ = $B⁻¹->multiply(B: $A⁻¹);

            // Then
            $this->assertEqualsWithDelta($⟮AB⟯⁻¹->getMatrix(),
                $B⁻¹A⁻¹->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: A is invertible, Aᵀ is inveritble
         * If A is an invertible matrix, then the transpose is also inveritble
         * @dataProvider dataProviderForInverse
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testIfMatrixIsInvertibleThenTransposeIsInvertible(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();

            // Then
            if ($A->isInvertible())
                $this->assertTrue($Aᵀ->isInvertible()); else
                $this->assertFalse($Aᵀ->isInvertible());
        }

        /**
         * @test         Axiom: A is invertible, AAᵀ is inveritble
         * If A is an invertible matrix, then the product of A and tranpose of A is also inveritble
         * @dataProvider dataProviderForInverse
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testIfMatrixIsInvertibleThenProductOfMatrixAndTransposeIsInvertible(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();
            $AAᵀ = $A->multiply(B: $Aᵀ);

            // then
            if ($A->isInvertible())
                $this->assertTrue($AAᵀ->isInvertible()); else
                $this->assertFalse($AAᵀ->isInvertible());
        }

        /**
         * @test         Axiom: A is invertible, AᵀA is inveritble
         * If A is an invertible matrix, then the product of transpose and A is also inveritble
         * @dataProvider dataProviderForInverse
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testIfMatrixIsInvertibleThenProductOfTransposeAndMatrixIsInvertible(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();
            $AᵀA = $Aᵀ->multiply(B: $A);

            // Then
            if ($A->isInvertible())
                $this->assertTrue($AᵀA->isInvertible()); else
                $this->assertFalse($AᵀA->isInvertible());
        }

        /**
         * @test         Axiom: (Aᵀ)ᵀ = A
         * The transpose of the transpose is the original matrix.
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testTransposeOfTransposeIsOriginalMatrix(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $⟮A⁻ᵀ⟯ᵀ = $A->transpose()->transpose();

            // Then
            $this->assertEquals($⟮A⁻ᵀ⟯ᵀ->getMatrix(), $A->getMatrix());
        }

        /**
         * @test         Axiom: (A⁻¹)ᵀ = (Aᵀ)⁻¹
         * The transpose of the inverse is the inverse of the transpose.
         *
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testTransposeOfInverseIsInverseOfTranspose(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $⟮A⁻¹⟯ᵀ = $A->inverse()->transpose();
            $⟮Aᵀ⟯⁻¹ = $A->transpose()->inverse();

            // Then
            $this->assertEqualsWithDelta($⟮A⁻¹⟯ᵀ->getMatrix(),
                $⟮Aᵀ⟯⁻¹->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: (rA)ᵀ = rAᵀ
         * Scalar multiplication order does not matter for transpose
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testScalarMultiplicationOfTransposeOrder(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $r = 4;

            // When
            $⟮rA⟯ᵀ = $A->scalarMultiply(λ: $r)->transpose();
            $rAᵀ = $A->transpose()->scalarMultiply(λ: $r);

            // Then
            $this->assertEquals($⟮rA⟯ᵀ->getMatrix(), $rAᵀ->getMatrix());
        }

        /**
         * @test         Axiom: (AB)ᵀ = BᵀAᵀ
         * Transpose of a product of matrices equals the product of their transposes in reverse order.
         *
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testTransposeProductIsProductOfTranposesInReverseOrder(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When (AB)ᵀ
            $⟮AB⟯ᵀ = $A->multiply(B: $B)->transpose();

            // And BᵀAᵀ
            $Bᵀ = $B->transpose();
            $Aᵀ = $A->transpose();
            $BᵀAᵀ = $Bᵀ->multiply(B: $Aᵀ);

            // Then
            $this->assertEquals($⟮AB⟯ᵀ->getMatrix(), $BᵀAᵀ->getMatrix());
        }

        /**
         * @test         Axiom: (A + B)ᵀ = Aᵀ + Bᵀ
         * Transpose of sum is the same as sum of transposes
         *
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testTransposeSumIsSameAsSumOfTransposes(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When (A + B)ᵀ
            $⟮A＋B⟯ᵀ = $A->add(B: $B)->transpose();

            // And Aᵀ + Bᵀ
            $Aᵀ = $A->transpose();
            $Bᵀ = $B->transpose();
            $Aᵀ＋Bᵀ = $Aᵀ->add(B: $Bᵀ);

            // Then
            $this->assertEquals($⟮A＋B⟯ᵀ->getMatrix(), $Aᵀ＋Bᵀ->getMatrix());
        }

        /**
         * @test         Axiom: tr(A) = tr(Aᵀ)
         * Trace is the same as the trace of the transpose
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testTraceIsSameAsTraceOfTranspose(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();
            $tr⟮A⟯ = $A->trace();
            $tr⟮Aᵀ⟯ = $Aᵀ->trace();

            // Then
            $this->assertEquals($tr⟮A⟯, $tr⟮Aᵀ⟯);
        }

        /**
         * @test         Axiom: tr(AB) = tr(BA)
         * Trace of product does not matter the order they were multiplied
         *
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testTraceOfProductIsSameRegardlessOfOrderMultiplied(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $tr⟮AB⟯ = $A->multiply(B: $B)->trace();
            $tr⟮BA⟯ = $B->multiply(B: $A)->trace();

            // Then
            $this->assertEquals($tr⟮AB⟯, $tr⟮BA⟯);
        }

        /**
         * @test         Axiom: det(A) = det(Aᵀ)
         * Determinant of matrix is the same as determinant of transpose.
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testDeterminantSameAsDeterminantOfTranspose(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $det⟮A⟯ = $A->det();
            $det⟮Aᵀ⟯ = $A->transpose()->det();

            // Then
            $this->assertEqualsWithDelta($det⟮A⟯, $det⟮Aᵀ⟯, 0.00001);
        }

        /**
         * @test         Axiom: det(AB) = det(A)det(B)
         * Determinant of product of matrices is the same as the product of determinants.
         *
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testDeterminantProductSameAsProductOfDeterminants(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When det(AB)
            $det⟮AB⟯ = $A->multiply(B: $B)->det();

            // And det(A)det(B)
            $det⟮A⟯ = $A->det();
            $det⟮B⟯ = $B->det();
            $det⟮A⟯det⟮B⟯ = $det⟮A⟯ * $det⟮B⟯;

            // Then
            $this->assertEqualsWithDelta($det⟮AB⟯, $det⟮A⟯det⟮B⟯, 0.000001);
        }

        /**
         * @test         Axiom: PA = LU
         * Basic LU decomposition property that permutation matrix times the matrix is the product of the lower and upper decomposition matrices.
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testLUDecompositionPAEqualsLU(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $LUP = $A->luDecomposition();
            $L = $LUP->L;
            $U = $LUP->U;
            $P = $LUP->P;

            // Then PA = LU;
            $PA = $P->multiply(B: $A);
            $LU = $L->multiply(B: $U);
            $this->assertEqualsWithDelta($PA->getMatrix(), $LU->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: A = P⁻¹LU
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testLUDecompositionAEqualsPInverseLU(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $LUP = $A->luDecomposition();
            $L = $LUP->L;
            $U = $LUP->U;
            $P = $LUP->P;

            // Then A = P⁻¹LU
            $P⁻¹LU = $P->inverse()->multiply(B: $L)->multiply(B: $U);
            $this->assertEqualsWithDelta($A->getMatrix(), $P⁻¹LU->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: PPᵀ = I = PᵀP
         * Permutation matrix of the LU decomposition times the transpose of the permutation matrix is the identity matrix.
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testLUDecompositionPPTransposeEqualsIdentity(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $LUP = $A->luDecomposition();

            $P = $LUP->P;
            $Pᵀ = $P->transpose();

            $PPᵀ = $P->multiply(B: $Pᵀ);
            $PᵀP = $Pᵀ->multiply(B: $P);

            $I = MatrixFactory::identity(n: $A->getM());

            // Then PPᵀ = I = PᵀP
            $this->assertEquals($PPᵀ->getMatrix(), $I->getMatrix());
            $this->assertEquals($I->getMatrix(), $PᵀP->getMatrix());
            $this->assertEquals($PPᵀ->getMatrix(), $PᵀP->getMatrix());
        }

        /**
         * @test         Axiom: (PA)⁻¹ = (LU)⁻¹ = U⁻¹L⁻¹
         * Inverse of the LU decomposition equation is the inverse of the other side.
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testInverseWithLUDecompositionInverse(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $LUP = $A->luDecomposition();
            $L = $LUP->L;
            $U = $LUP->U;
            $P = $LUP->P;

            $⟮PA⟯⁻¹ = $P->multiply(B: $A)->inverse();
            $⟮LU⟯⁻¹ = $L->multiply(B: $U)->inverse();

            $U⁻¹ = $U->inverse();
            $L⁻¹ = $L->inverse();
            $U⁻¹L⁻¹ = $U⁻¹->multiply(B: $L⁻¹);

            // Then (PA)⁻¹ = (LU)⁻¹ = U⁻¹L⁻¹
            $this->assertEqualsWithDelta($⟮PA⟯⁻¹->getMatrix(),
                $⟮LU⟯⁻¹->getMatrix(), 0.00001);
            $this->assertEqualsWithDelta($⟮LU⟯⁻¹->getMatrix(),
                $U⁻¹L⁻¹->getMatrix(), 0.00001);
            $this->assertEqualsWithDelta($⟮PA⟯⁻¹->getMatrix(),
                $U⁻¹L⁻¹->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: A = QR
         * Basic QR decomposition property that A = QR
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForNotSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         * @dataProvider dataProviderForNotSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testQRDecompositionAEqualsQR(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $qr = $A->qrDecomposition();
            $Q = $qr->Q;
            $R = $qr->R;

            // Then A = QR
            $this->assertEqualsWithDelta($A->getMatrix(),
                $Q->multiply(B: $R)->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: QR.Q is orthogonal and QR.R is upper triangular
         * QR decomposition properties Q is orthogonal and R is upper triangular
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testQRDecompositionQOrthogonalRUpperTriangular(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $qr = $A->qrDecomposition();

            // Then Q is orthogonal and R is upper triangular
            $this->assertTrue($qr->Q->isOrthogonal());
            $this->assertTrue($qr->R->isUpperTriangular());
        }

        /**
         * @test         Axiom QᵀQ = I
         *               QR decomposition property orthonormal matrix Q has the property QᵀQ = I
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForNotSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         * @dataProvider dataProviderForNotSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testQrDecompositionOrthonormalMatrixQPropertyQTransposeQIsIdentity(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $I = MatrixFactory::identity(n: min($A->getM(), $A->getN()));

            // And
            $qr = $A->qrDecomposition();

            // When
            $QᵀQ = $qr->Q->transpose()->multiply(B: $qr->Q);

            // Then QᵀQ = I
            $this->assertEqualsWithDelta($I->getMatrix(), $QᵀQ->getMatrix(),
                0.000001);
        }

        /**
         * @test         Axiom R = QᵀA
         *               QR decomposition property R = QᵀA
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForNotSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         * @dataProvider dataProviderForNotSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testQrDecompositionPropertyREqualsQTransposeA(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // And
            $qrDecomposition = $A->qrDecomposition();

            // When
            $QᵀA = $qrDecomposition->Q->transpose()->multiply(B: $A);

            // Then R = QᵀA
            $this->assertEqualsWithDelta($qrDecomposition->R->getMatrix(),
                $QᵀA->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom Qᵀ = Q⁻¹
         *               QR decomposition property Qᵀ = Q⁻¹
         * @dataProvider dataProviderForSquareMatrix
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testQrDecompositionPropertyQTransposeEqualsQInverse(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // And
            $Q = $A->qrDecomposition()->Q;

            // When
            $Qᵀ = $Q->transpose();
            $Q⁻¹ = $Q->inverse();

            // Then Qᵀ = Q⁻¹
            $this->assertEqualsWithDelta($Qᵀ->getMatrix(), $Q⁻¹->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: A = LU where L = LD
         * Basic Crout decomposition property that A = (LD)U
         * @dataProvider dataProviderForSquareMatrixGreaterThanOneWithoutOddMatrices
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCroutDecompositionAEqualsLDU(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $crout = $A->croutDecomposition();
            $L = $crout->L;
            $U = $crout->U;

            // Then A = LU
            $this->assertEqualsWithDelta($A->getMatrix(),
                $L->multiply(B: $U)->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: A = LLᵀ
         * Basic Cholesky decomposition property that A = LLᵀ
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCholeskyDecompositionAEqualsLLᵀ(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $cholesky = $A->choleskyDecomposition();
            $L = $cholesky->L;
            $Lᵀ = $cholesky->LT;

            // Then A = LLᵀ
            $this->assertEqualsWithDelta($A->getMatrix(),
                $L->multiply(B: $Lᵀ)->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: P⁻¹ = Pᵀ
         * Inverse of the permutation matrix equals the transpose of the permutation matrix
         *
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPInverseEqualsPTranspose(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $LUP = $A->luDecomposition();
            $P = $LUP->P;
            $P⁻¹ = $P->inverse();
            $Pᵀ = $P->transpose();

            // Then
            $this->assertEquals($P⁻¹, $Pᵀ);
        }

        /**
         * @test         Axiom: Ax - b = 0
         * Matrix multiplied with unknown x vector subtract solution b is 0
         *
         * @dataProvider dataProviderForSolve
         *
         * @param array $A
         * @param array $b
         * @param array $x
         *
         * @throws       \Exception
         */
        public function testSolveEquationForZero(array $A, array $b, array $x)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $x = new Vector(A: $x);
            $b = (new Vector(A: $b))->asColumnMatrix();

            // And zeros
            $z = (new Vector(A: array_fill(start_index: 0,
                count: count(value: $x), value: 0)))->asColumnMatrix();

            // When Ax - b
            $R = $A->multiply(B: $x)->subtract(B: $b);

            // Then
            $this->assertEqualsWithDelta($z, $R, 0.01);
        }

        /**
         * @test         Axiom: x = A⁻¹b
         * Inverse of A multiplied with b is a solution to x
         *
         * @dataProvider dataProviderForSolve
         *
         * @param array $A
         * @param array $b
         * @param array $x
         *
         * @throws       \Exception
         */
        public function testSolveInverseBEqualsX(array $A, array $b, array $x)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $A⁻¹ = $A->inverse();
            $x = (new Vector(A: $x))->asColumnMatrix();
            $b = new Vector(A: $b);

            // When A⁻¹b
            $A⁻¹b = $A⁻¹->multiply(B: $b);

            // Then
            $this->assertEqualsWithDelta($x, $A⁻¹b, 0.001);
        }

        /**
         * @test         Axiom: LU: Ly = Pb and Ux = y
         * LU decomposition provides a solution to Ax = b
         *
         * @dataProvider dataProviderForSolve
         *
         * @param array $A
         * @param array $b
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testSolveLuDecomposition(
            array $A,
            array $b,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expected = new Vector(A: $expected);

            // And
            $LU = $A->luDecomposition();

            // When Ly = Pb and Ux = y
            $x = $LU->solve(b: $b);

            // Then
            $this->assertEqualsWithDelta($expected, $x, 0.001);
        }

        /**
         * @test         Axiom: QR: x = R⁻¹Qᵀb
         * QR decomposition provides a solution to Ax = b
         *
         * @dataProvider dataProviderForSolve
         *
         * @param array $A
         * @param array $b
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testSolveQrDecomposition(
            array $A,
            array $b,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expected = new Vector(A: $expected);

            // And
            $QR = $A->qrDecomposition();

            // When x = R⁻¹Qᵀb
            $x = $QR->solve(b: $b);

            // Then
            $this->assertEqualsWithDelta($expected, $x, 0.001);
        }

        /**
         * @test         Axiom: RREF of A augmented with B provides a solution to Ax = b
         *
         * @dataProvider dataProviderForSolve
         *
         * @param array $A
         * @param array $b
         * @param array $expected
         *
         * @throws       \Exception
         */
        public function testSolveRref(array $A, array $b, array $expected)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $b = new Vector(A: $b);
            $expected = new Vector(A: $expected);

            // And
            $QR = $A->qrDecomposition();

            // When RREF
            $Ab = $A->augment(B: $b->asColumnMatrix());
            $rref = $Ab->rref();
            $x = new Vector(A: array_column(array: $rref->getMatrix(),
                column_key: $rref->getN() - 1));

            // Then
            $this->assertEqualsWithDelta($expected, $x, 0.001);
        }

        /**
         * @test         Axiom: Symmetric matrix is square
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSymmetricMatrixIsSquare(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $isSquare = $A->isSquare();

            // Then
            $this->assertTrue($isSquare);
        }

        /**
         * @test         Axiom: A = Aᵀ
         * Symmetric matrix is the same as its transpose
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSymmetricEqualsTranspose(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();

            // Then
            $this->assertEquals($A->getMatrix(), $Aᵀ->getMatrix());
        }

        /**
         * @test         Axiom: A⁻¹Aᵀ = I
         * Symmetric matrix inverse times transpose equals identity matrix
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSymmetricInverseTransposeEqualsIdentity(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();
            $Aᵀ = $A->transpose();

            // And
            $A⁻¹Aᵀ = $A⁻¹->multiply(B: $Aᵀ);
            $I = MatrixFactory::identity(n: $A->getM());

            // Then
            $this->assertEqualsWithDelta($I, $A⁻¹Aᵀ, 0.00001);
            $this->assertEqualsWithDelta($I->getMatrix(), $A⁻¹Aᵀ->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: A + B is symmetric
         * If A and B are symmetric matrices with the sme size, then A + B is symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testSymmetricMatricesSumIsSymmetric(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);
            $B = MatrixFactory::create(A: $M);

            // When
            $A＋B = $A->add(B: $B);

            // Then
            $this->assertTrue($A->isSymmetric());
            $this->assertTrue($B->isSymmetric());
            $this->assertTrue($A＋B->isSymmetric());
        }

        /**
         * @test         Axiom: A - B is symmetric
         * If A and B are symmetric matrices with the sme size, then A - B is symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testSymmetricMatricesDifferenceIsSymmetric(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);
            $B = MatrixFactory::create(A: $M);

            // When
            $A−B = $A->subtract(B: $B);

            // Then
            $this->assertTrue($A->isSymmetric());
            $this->assertTrue($B->isSymmetric());
            $this->assertTrue($A−B->isSymmetric());
        }

        /**
         * @test         Axiom: kA is symmetric
         * If A is a symmetric matrix, kA is symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testSymmetricMatricesTimesScalarIsSymmetric(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);
            $this->assertTrue($A->isSymmetric());

            foreach (range(start: 1, end: 10) as $k)
            {
                // When
                $kA = $A->scalarMultiply(λ: $k);

                // Then
                $this->assertTrue($kA->isSymmetric());
            }
        }

        /**
         * @test         Axiom: AAᵀ is symmetric
         * If A is a symmetric matrix, AAᵀ is symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testSymmetricMatrixTimesTransposeIsSymmetric(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);

            // When
            $Aᵀ = $A->transpose();
            $AAᵀ = $A->multiply(B: $Aᵀ);

            // Then
            $this->assertTrue($A->isSymmetric());
            $this->assertTrue($AAᵀ->isSymmetric());
        }

        /**
         * @test         Axiom: AᵀA is symmetric
         * If A is a symmetric matrix, AᵀA is symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testTransposeTimesSymmetricMatrixIsSymmetric(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);

            // When
            $Aᵀ = $A->transpose();
            $AᵀA = $Aᵀ->multiply(B: $A);

            // Then
            $this->assertTrue($A->isSymmetric());
            $this->assertTrue($AᵀA->isSymmetric());
        }

        /**
         * @test         Axiom: A is invertible symmetric, A⁻¹ is symmetric
         * If A is an invertible symmetric matrix, the inverse of A is also symmetric
         * @dataProvider dataProviderForSymmetricMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testMatrixIsInvertibleSymmetricThenInverseIsSymmetric(
            array $M
        ) {
            // Given
            $A = MatrixFactory::create(A: $M);

            if ($A->isInvertible() && $A->isSymmetric())
            {
                // When
                $A⁻¹ = $A->inverse();
                $A⁻¹ = $A⁻¹->map(
                    // Floating point adjustment
                    fn($x) => round($x, 5)
                );

                // Theb
                $this->assertTrue($A⁻¹->isSymmetric());
            }
        }

        /**
         * @test         Axiom: A is skew-symmetric, det(A) ≥ 0
         *               If A is a skew-symmetric matrix, the determinant is greater than zero.
         * @dataProvider dataProviderForSkewSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testMatrixIsSkewSymmetricDeterminantGreaterThanZero(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isSkewSymmetric());
            $this->assertGreaterThanOrEqual(0, $A->det());
        }

        /**
         * @test         Axiom: The sum of two skew-symmetric matrices is skew-symmetric
         * @dataProvider dataProviderForSkewSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSumOfTwoSkewSymmetricMatricesIsSkewSymmetric(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $B = $A->add(B: $A);

            // Then
            $this->assertTrue($B->isSkewSymmetric());
        }

        /**
         * @test         Axiom: Scalar multiple of a skew-symmetric matrix is skew-symmetric
         * @dataProvider dataProviderForSkewSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testScalarMultipleOfSkewSymmetricMatrixIsSkewSymmetric(
            array $A
        ) {
            $A = MatrixFactory::create(A: $A);

            $B = $A->scalarMultiply(λ: 6);
            $this->assertTrue($B->isSkewSymmetric());
        }

        /**
         * @test         Axiom: The elements on the diagonal of a skew-symmetric matrix are zero, and therefore also its trace
         * @dataProvider dataProviderForSkewSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSkewSymmetricMatrixDiagonalElementsAreZeroAndThereforeTraceIsZero(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            foreach ($A->getDiagonalElements() as $diagonal_element)
                $this->assertEquals(0, $diagonal_element);

            // And When
            $trace = $A->trace();

            // Then
            $this->assertEquals(0, $trace);
        }

        /**
         * @test         Axiom: A is a real skew-symmetric matrix, then I+A is invertible, where I is the identity matrix
         * @dataProvider dataProviderForSkewSymmetricMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSkewSymmetricMatrixAddedToIdentityIsInvertible(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $I = MatrixFactory::identity(n: $A->getN());

            // When
            $I＋A = $I->add(B: $A);

            // Then
            $this->assertTrue($I＋A->isInvertible());
        }

        /**
         * @test         Axiom: A ⊗ (B + C) = A ⊗ B + A ⊗ C
         * Kronecker product bilinearity
         * @dataProvider dataProviderForThreeMatrices
         *
         * @param array $A
         * @param array $B
         * @param array $C
         *
         * @throws       \Exception
         */
        public function testKroneckerProductBilinearity1(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);

            // When
            $A⊗⟮B＋C⟯ = $A->kroneckerProduct(B: $B->add(B: $C));
            $A⊗B＋A⊗C = $A->kroneckerProduct(B: $B)->add(B: $A->kroneckerProduct(B: $C));

            // Then
            $this->assertEquals($A⊗⟮B＋C⟯->getMatrix(), $A⊗B＋A⊗C->getMatrix());
        }

        /**
         * @test         Axiom: (A + B) ⊗ C = A ⊗ C + B ⊗ C
         * Kronecker product bilinearity
         * @dataProvider dataProviderForThreeMatrices
         *
         * @param array $A
         * @param array $B
         * @param array $C
         *
         * @throws       \Exception
         */
        public function testKroneckerProductBilinearity2(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);

            // When
            $⟮A＋B⟯⊗C = $A->add(B: $B)->kroneckerProduct(B: $C);
            $A⊗C＋B⊗C = $A->kroneckerProduct(B: $C)->add(B: $B->kroneckerProduct(B: $C));

            // Then
            $this->assertEquals($⟮A＋B⟯⊗C->getMatrix(), $A⊗C＋B⊗C->getMatrix());
        }

        /**
         * @test         Axiom: (A ⊗ B) ⊗ C = A ⊗ (B ⊗ C)
         * Kronecker product associative
         * @dataProvider dataProviderForThreeMatrices
         *
         * @param array $A
         * @param array $B
         * @param array $C
         *
         * @throws       \Exception
         */
        public function testKroneckerProductAssociativity(
            array $A,
            array $B,
            array $C
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);

            // When
            $⟮A⊗B⟯⊗C = $A->kroneckerProduct(B: $B)->kroneckerProduct(B: $C);
            $A⊗⟮B⊗C⟯ = $A->kroneckerProduct(B: $B->kroneckerProduct(B: $C));

            // Then
            $this->assertEquals($⟮A⊗B⟯⊗C->getMatrix(), $A⊗⟮B⊗C⟯->getMatrix());
        }

        /**
         * @test         Axiom: (A ⊗ B)(C ⊗ D) = AC ⊗ BD
         *               Kronecker multiplication
         * @dataProvider dataProviderForFourMatrices
         *
         * @param array $A
         * @param array $B
         * @param array $C
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testKroneckerProductMultiplication(
            array $A,
            array $B,
            array $C,
            array $D
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $C = MatrixFactory::create(A: $C);
            $D = MatrixFactory::create(A: $D);

            // When
            $⟮A⊗B⟯ = $A->kroneckerProduct(B: $B);
            $⟮C⊗D⟯ = $C->kroneckerProduct(B: $D);
            $⟮A⊗B⟯⟮C⊗D⟯ = $⟮A⊗B⟯->multiply(B: $⟮C⊗D⟯);

            // And
            $AC = $A->multiply(B: $C);
            $BD = $B->multiply(B: $D);
            $AC⊗BD = $AC->kroneckerProduct(B: $BD);

            // Theb
            $this->assertEquals($⟮A⊗B⟯⟮C⊗D⟯->getMatrix(), $AC⊗BD->getMatrix());
        }

        /**
         * @test         Axiom: (kA) ⊗ B = A ⊗ (kB) = k(A ⊗ B)
         * Kronecker product scalar multiplication
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testKroneckerProductScalarMultiplication(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $k = 5;

            // When
            $⟮kA⟯⊗B = $A->scalarMultiply(λ: $k)->kroneckerProduct(B: $B);
            $A⊗⟮kB⟯ = $A->kroneckerProduct(B: $B->scalarMultiply(λ: $k));
            $k⟮A⊗B⟯ = $A->kroneckerProduct(B: $B)->scalarMultiply(λ: $k);

            // Then
            $this->assertEquals($⟮kA⟯⊗B->getMatrix(), $A⊗⟮kB⟯->getMatrix());
            $this->assertEquals($⟮kA⟯⊗B->getMatrix(), $k⟮A⊗B⟯->getMatrix());
            $this->assertEquals($k⟮A⊗B⟯->getMatrix(), $A⊗⟮kB⟯->getMatrix());
        }

        /**
         * @test         Axiom: (A ⊗ B)⁻¹ = A⁻¹ ⊗ B⁻¹
         * Inverse of Kronecker product
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testKroneckerProductInverse(array $A, array $B)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $A⁻¹ = $A->inverse();
            $B⁻¹ = $B->inverse();
            $A⁻¹⊗B⁻¹ = $A⁻¹->kroneckerProduct(B: $B⁻¹);
            $⟮A⊗B⟯⁻¹ = $A->kroneckerProduct(B: $B)->inverse();

            // Then
            $this->assertEqualsWithDelta($A⁻¹⊗B⁻¹->getMatrix(),
                $⟮A⊗B⟯⁻¹->getMatrix(), 0.00001);
        }

        /**
         * @test         Axiom: (A ⊗ B)ᵀ = Aᵀ ⊗ Bᵀ
         * Transpose of Kronecker product
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testKroneckerProductTranspose(array $A, array $B)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $Aᵀ = $A->transpose();
            $Bᵀ = $B->transpose();
            $Aᵀ⊗Bᵀ = $Aᵀ->kroneckerProduct(B: $Bᵀ);
            $⟮A⊗B⟯ᵀ = $A->kroneckerProduct(B: $B)->transpose();

            // Then
            $this->assertEquals($Aᵀ⊗Bᵀ->getMatrix(), $⟮A⊗B⟯ᵀ->getMatrix());
        }

        /**
         * @test         Axiom: det(A ⊗ B) = det(A)ᵐ det(B)ⁿ
         * Determinant of Kronecker product - where A is nxn matrix, and b is nxn matrix
         * @dataProvider dataProviderForKroneckerProductDeterminant
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testKroneckerProductDeterminant(array $A, array $B)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $det⟮A⟯ᵐ = ($A->det()) ** $B->getM();
            $det⟮B⟯ⁿ = ($B->det()) ** $A->getN();
            $det⟮A⊗B⟯ = $A->kroneckerProduct(B: $B)->det();

            // Then
            $this->assertEqualsWithDelta($det⟮A⊗B⟯, $det⟮A⟯ᵐ * $det⟮B⟯ⁿ,
                0.0001);
        }

        /**
         * @test         Axiom: Kronecker sum A⊕B = A⊗Ib + I⊗aB
         * Kronecker sum is the matrix product of the Kronecker product of each matrix with the other matrix's identiry matrix.
         * @dataProvider dataProviderForTwoSquareMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testKroneckerSum(array $A, array $B)
        {
            // Given
            $A = new NumericSquareMatrix(A: $A);
            $B = new NumericSquareMatrix(A: $B);
            $A⊕B = $A->kroneckerSum(B: $B);

            // When
            $In = MatrixFactory::identity(n: $A->getN());
            $Im = MatrixFactory::identity(n: $B->getM());
            $A⊗Im = $A->kroneckerProduct(B: $Im);
            $In⊗B = $In->kroneckerProduct(B: $B);
            $A⊗Im＋In⊗B = $A⊗Im->add(B: $In⊗B);

            // Then
            $this->assertEquals($A⊕B, $A⊗Im＋In⊗B);
        }

        /**
         * @test         Axiom: Covariance matrix S = Sᵀ
         * Covariance matrix is symmetric so it is the same as its transpose
         * @dataProvider dataProviderForCovarianceSymmetric
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCovarianceMatrixIsSymmetric(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $S = $A->covarianceMatrix();
            $Sᵀ = $S->transpose();

            // Then
            $this->assertEquals($S->getMatrix(), $Sᵀ->getMatrix());
        }

        /**
         * @test         Axiom: Positive definiteness A is PD ⇔ -A is ND
         * If A is positive definite, then -A is negative definite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteNegativeisNegativeDefinite(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $−A = $A->scalarMultiply(λ: -1);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($−A->isNegativeDefinite());
        }

        /**
         * @test         Axiom: Positive semidefiniteness A is PSD ⇔ -A is NSD
         * If A is positive semidefinite, then -A is negative definite.
         * @dataProvider dataProviderForPositiveSemidefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPositiveSemidefiniteNegativeisNegativeSemidefinite(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $−A = $A->scalarMultiply(λ: -1);

            // Then
            $this->assertTrue($A->isPositiveSemidefinite());
            $this->assertTrue($−A->isNegativeSemidefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A is PD ⇒ A is PSD
         * If A is positive definite, then A is also positive semidefinite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteIsAlsoPositiveSemidefinite(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($A->isPositiveSemidefinite());
        }

        /**
         * @test         Axiom: Negative definiteness A is ND ⇒ A is NSD
         * If A is negative definite, then A is also negative semidefinite.
         * @dataProvider dataProviderForNegativeDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testNegativeDefiniteIsAlsoNegativeSemidefinite(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isNegativeDefinite());
            $this->assertTrue($A->isNegativeSemidefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A is PD ⇔ A⁻¹ is PD
         * If A is positive definite, then A⁻¹ is positive definite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteInverseIsPositiveDefinite(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();

            // Floating point adjustment
            $A⁻¹ = $A⁻¹->map(fn($x) => round($x, 7));

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($A⁻¹->isPositiveDefinite());
        }

        /**
         * @test         Axiom: Negative definiteness A is ND ⇔ A⁻¹ is ND
         * If A is negative definite, then A⁻¹ is negative definite.
         * @dataProvider dataProviderForNegativeDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testNegativeDefiniteInverseIsNegativeDefinite(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();

            // Floating point adjustment
            $A⁻¹ = $A⁻¹->map(fn($x) => round($x, 7));

            // Then
            $this->assertTrue($A->isNegativeDefinite());
            $this->assertTrue($A⁻¹->isNegativeDefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A is PD and r > 0 ⇒ rA is PD
         * If A is positive definite and r > 0, then rA is positive definite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteThenScalarMultiplyWithPositiveNumberIsPositiveDefinite(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $this->assertTrue($A->isPositiveDefinite());

            foreach (range(start: 1, end: 10) as $r)
            {
                // When
                $rA = $A->scalarMultiply(λ: $r);

                // Then
                $this->assertTrue($rA->isPositiveDefinite());
            }
        }

        /**
         * @test         Axiom: Positive definiteness A and B are PD ⇒ A + B is PD
         * If A and B are positive definite then A + B is positive definite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteAPlusAIsPositiveDefinite(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);
            $B = MatrixFactory::create(A: $M);

            // When
            $A＋B = $A->add(B: $B);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($B->isPositiveDefinite());
            $this->assertTrue($A＋B->isPositiveDefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A and B are PD ⇒ A + B is PD
         * If A and B are positive definite then A + B is positive definite.
         * @dataProvider dataProviderForTwoPositiveDefiniteMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteAPlusBIsPositiveDefinite(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $A＋B = $A->add(B: $B);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($B->isPositiveDefinite());
            $this->assertTrue($A＋B->isPositiveDefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A and B are PD ⇒ ABA is PD
         * If A and B are positive definite then ABA is positive definite.
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $M
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteAAAIsPositiveDefinite(array $M)
        {
            // Given
            $A = MatrixFactory::create(A: $M);
            $B = MatrixFactory::create(A: $M);

            // When
            $ABA = $A->multiply(B: $B)->multiply(B: $A);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($B->isPositiveDefinite());
            $this->assertTrue($ABA->isPositiveDefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A and B are PD ⇒ ABA is PD
         * If A and B are positive definite then ABA is positive definite.
         * @dataProvider dataProviderForTwoPositiveDefiniteMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteABAIsPositiveDefinite(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $ABA = $A->multiply(B: $B)->multiply(B: $A);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($B->isPositiveDefinite());
            $this->assertTrue($ABA->isPositiveDefinite());
        }

        /**
         * @test         Axiom: Positive definiteness A and B are PD ⇒ BAB is PD
         * If A and B are positive definite then BAB is positive definite.
         * @dataProvider dataProviderForTwoPositiveDefiniteMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testPositiveDefiniteBABIsPositiveDefinite(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $BAB = $B->multiply(B: $A)->multiply(B: $B);

            // Then
            $this->assertTrue($A->isPositiveDefinite());
            $this->assertTrue($B->isPositiveDefinite());
            $this->assertTrue($BAB->isPositiveDefinite());
        }

        /**
         * @test Axiom: Zero matrix is lower triangular
         * @throws   \Exception
         */
        public function testZeroMatrixIsLowerTriangular()
        {
            foreach (range(start: 1, end: 20) as $m)
            {
                // Given
                $L = MatrixFactory::zero(m: $m, n: $m);

                // Then
                $this->assertTrue($L->isLowerTriangular());
            }
        }

        /**
         * @test Axiom: Zero matrix is upper triangular
         * @throws   \Exception
         */
        public function testZeroMatrixIsUpperTriangular()
        {
            foreach (range(start: 1, end: 20) as $m)
            {
                // Given
                $L = MatrixFactory::zero(m: $m, n: $m);

                // Then
                $this->assertTrue($L->isUpperTriangular());
            }
        }

        /**
         * @test Axiom: Zero matrix is diagonal
         * @throws   \Exception
         */
        public function testZeroMatrixIsDiagonal()
        {
            foreach (range(start: 1, end: 20) as $m)
            {
                // Given
                $L = MatrixFactory::zero(m: $m, n: $m);

                // Then
                $this->assertTrue($L->isDiagonal());
            }
        }

        /**
         * @test         Axiom: Lᵀ is upper triangular
         * Transpose of a lower triangular matrix is upper triagular
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testTransposeOfLowerTriangularMatrixIsUpperTriangular(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);

            // When
            $Lᵀ = $L->Transpose();

            // Then
            $this->assertTrue($L->isLowerTriangular());
            $this->assertTrue($Lᵀ->isUpperTriangular());
        }

        /**
         * @test         Axiom: Uᵀ is lower triangular
         * Transpose of an upper triangular matrix is lower triagular
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testTransposeOfUpperTriangularMatrixIsLowerTriangular(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);

            // When
            $Uᵀ = $U->Transpose();

            // Then
            $this->assertTrue($U->isUpperTriangular());
            $this->assertTrue($Uᵀ->isLowerTriangular());
        }

        /**
         * @test         Axiom: LL is lower triangular
         * Product of two lower triangular matrices is lower triangular
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testProductOfTwoLowerTriangularMatricesIsLowerTriangular(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);

            // When
            $LL = $L->multiply(B: $L);

            // Then
            $this->assertTrue($L->isLowerTriangular());
            $this->assertTrue($LL->isLowerTriangular());
        }

        /**
         * @test         Axiom: UU is upper triangular
         * Product of two upper triangular matrices is upper triangular
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testProductOfTwoUpperTriangularMatricesIsUpperTriangular(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);

            // When
            $UU = $U->multiply(B: $U);

            // Then
            $this->assertTrue($U->isUpperTriangular());
            $this->assertTrue($UU->isUpperTriangular());
        }

        /**
         * @test         Axiom: L + L is lower triangular
         * Sum of two lower triangular matrices is lower triangular
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testSumOfTwoLowerTriangularMatricesIsLowerTriangular(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);

            // When
            $L＋L = $L->add(B: $L);

            // Then
            $this->assertTrue($L->isLowerTriangular());
            $this->assertTrue($L＋L->isLowerTriangular());
        }

        /**
         * @test         Axiom: U + U is upper triangular
         * Sum of two upper triangular matrices is upper triangular
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testSumOfTwoUpperTriangularMatricesIsUpperTriangular(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);

            // When
            $U＋U = $U->add(B: $U);

            // Then
            $this->assertTrue($U->isUpperTriangular());
            $this->assertTrue($U＋U->isUpperTriangular());
        }

        /**
         * @test         Axiom: L⁻¹ is lower triangular (If L is invertible)
         * The inverse of an invertible lower triangular matrix is lower triangular
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testInverseOfInvertibleLowerTriangularMatrixIsLowerTriangular(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);
            $this->assertTrue($L->isLowerTriangular());

            if ($L->isInvertible())
            {
                // When
                $L⁻¹ = $L->inverse();

                // Then
                $this->assertTrue($L⁻¹->isLowerTriangular());
            }
        }

        /**
         * @test         Axiom: U⁻¹ is upper triangular (If U is invertible)
         * The inverse of an invertible upper triangular matrix is upper triangular
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testInverseOfInvertibleUpperTriangularMatrixIsUpperTriangular(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);
            $this->assertTrue($U->isUpperTriangular());

            if ($U->isInvertible())
            {
                // When
                $U⁻¹ = $U->inverse();

                // Then
                $this->assertTrue($U⁻¹->isUpperTriangular());
            }
        }

        /**
         * @test         Axiom: kL is lower triangular
         * Product of a lower triangular matrix by a constant is lower triangular
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testProductOfLowerTriangularMatrixByConstantIsLowerTriangular(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);
            $this->assertTrue($L->isLowerTriangular());

            foreach (range(start: 1, end: 10) as $k)
            {
                // When
                $kL = $L->scalarMultiply(λ: $k);

                // Then
                $this->assertTrue($kL->isLowerTriangular());
            }
        }

        /**
         * @test         Axiom: kU is upper triangular
         * Product of a upper triangular matrix by a constant is upper triangular
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testProductOfUpperTriangularMatrixByConstantIsUpperTriangular(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);
            $this->assertTrue($U->isUpperTriangular());

            foreach (range(start: 1, end: 10) as $k)
            {
                // When
                $kU = $U->scalarMultiply(λ: $k);

                // Then
                $this->assertTrue($kU->isUpperTriangular());
            }
        }

        /**
         * @test         Axiom: L is invertible iff diagonal is all non zero
         * Lower triangular matrix is invertible if and only if its diagonal entries are all non zero
         * @dataProvider dataProviderForLowerTriangularMatrix
         *
         * @param array $L
         *
         * @throws       \Exception
         */
        public function testLowerTriangularMatrixIsInvertibleIfAndOnlyIfDigaonalEntriesAreAllNonZero(
            array $L
        ) {
            // Given
            $L = MatrixFactory::create(A: $L);
            $this->assertTrue($L->isLowerTriangular());

            $array_filter = array_filter($L->getDiagonalElements(),
                function ($x) {
                    return $x == 0;
                });
            $zeros = $array_filter;

            // Then
            if (count(value: $zeros) == 0)
                $this->assertTrue($L->isInvertible()); else
                $this->assertFalse($L->isInvertible());
        }

        /**
         * @test         Axiom: U is invertible iff diagonal is all non zero
         * Upper triangular matrix is invertible if and only if its diagonal entries are all non zero
         * @dataProvider dataProviderForUpperTriangularMatrix
         *
         * @param array $U
         *
         * @throws       \Exception
         */
        public function testUpperTriangularMatrixIsInvertibleIfAndOnlyIfDigaonalEntriesAreAllNonZero(
            array $U
        ) {
            // Given
            $U = MatrixFactory::create(A: $U);
            $this->assertTrue($U->isUpperTriangular());

            $array_filter = array_filter($U->getDiagonalElements(),
                function ($x) {
                    return $x == 0;
                });
            $zeros = $array_filter;

            // Then
            if (count(value: $zeros) == 0)
                $this->assertTrue($U->isInvertible()); else
                $this->assertFalse($U->isInvertible());
        }

        /**
         * @test         Axiom: Dᵀ is diagonal
         * Transpose of a diagonal matrix is diagonal
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testTransposeOfDiagonalMatrixIsDiagonal(array $D)
        {
            // Given
            $D = MatrixFactory::create(A: $D);

            // When
            $Dᵀ = $D->Transpose();

            // Then
            $this->assertTrue($D->isDiagonal());
            $this->assertTrue($Dᵀ->isDiagonal());
        }

        /**
         * @test         Axiom: DD is diagonal
         * Product of two diagonal matrices is diagonal
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testProductOfTwoDiagonalMatricesIsDiagonal(array $D)
        {
            // Given
            $D = MatrixFactory::create(A: $D);

            // When
            $DD = $D->multiply(B: $D);

            // Then
            $this->assertTrue($D->isDiagonal());
            $this->assertTrue($DD->isDiagonal());
        }

        /**
         * @test         Axiom: D + D is diagonal
         * Sum of two diagonal matrices is diagonal
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testSumOfTwoDiagonalMatricesIsDiagonal(array $D)
        {
            // Given
            $D = MatrixFactory::create(A: $D);

            // When
            $D＋D = $D->add(B: $D);

            // Then
            $this->assertTrue($D->isDiagonal());
            $this->assertTrue($D＋D->isDiagonal());
        }

        /**
         * @test         Axiom: D⁻¹ is diagonal (If D is invertible)
         * The inverse of an invertible diagonal matrix is diagonal
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testInverseOfInvertibleDiagonalMatrixIsDiagonal(array $D
        ) {
            // Given
            $D = MatrixFactory::create(A: $D);
            $this->assertTrue($D->isDiagonal());

            if ($D->isInvertible())
            {
                // When
                $D⁻¹ = $D->inverse();
                // Then
                $this->assertTrue($D⁻¹->isDiagonal());
            }
        }

        /**
         * @test         Axiom: kD is Diagonal
         * Product of a diagonal matrix by a constant is diagonal
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testProductOfDiagonalMatrixByConstantIsDiagonal(array $D
        ) {
            // Given
            $D = MatrixFactory::create(A: $D);
            $this->assertTrue($D->isDiagonal());

            foreach (range(start: 1, end: 10) as $k)
            {
                // When
                $kD = $D->scalarMultiply(λ: $k);
                // Then
                $this->assertTrue($kD->isDiagonal());
            }
        }

        /**
         * @test         Axiom: D is invertible iff diagonal is all non zero
         * Diagonal matrix is invertible if and only if its diagonal entries are all non zero
         * @dataProvider dataProviderForDiagonalMatrix
         *
         * @param array $D
         *
         * @throws       \Exception
         */
        public function testDiagonalMatrixIsInvertibleIfAndOnlyIfDigaonalEntriesAreAllNonZero(
            array $D
        ) {
            // Given
            $D = MatrixFactory::create(A: $D);
            $this->assertTrue($D->isDiagonal());

            $array_filter = array_filter($D->getDiagonalElements(),
                function ($x) {
                    return $x == 0;
                });
            $zeros = $array_filter;

            // Then
            if (count(value: $zeros) == 0)
                $this->assertTrue($D->isInvertible()); else
                $this->assertFalse($D->isInvertible());
        }

        /**
         * @test         Axiom: Reduced row echelon form is upper triangular
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testReducedRowEchelonFormIsUpperTriangular(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $rref = $A->rref();

            // Then
            $this->assertTrue($rref->isUpperTriangular());
        }

        /**
         * @test Axiom: Jᵀ = J
         * Transpose of an exchange matrix is itself
         * @throws \Exception
         */
        public function testTransposeOfExchangeMatrix()
        {
            foreach (range(start: 1, end: 20) as $n)
            {
                // Given
                $J = MatrixFactory::exchange(n: $n);
                // When
                $Jᵀ = $J->transpose();
                // Then
                $this->assertEquals($J->getMatrix(), $Jᵀ->getMatrix());
            }
        }

        /**
         * @test Axiom: J⁻¹ = J
         * Inverse of an exchange matrix is itself
         * @throws \Exception
         */
        public function testInverseOfExchangeMatrix()
        {
            foreach (range(start: 1, end: 20) as $n)
            {
                // Given
                $J = MatrixFactory::exchange(n: $n);
                // When
                $J⁻¹ = $J->inverse();
                // Then
                $this->assertEquals($J->getMatrix(), $J⁻¹->getMatrix());
            }
        }

        /**
         * @test Axiom: tr(J) is 1 if n is odd, and 0 if n is even
         * Trace of J is 1 if n is odd, and 0 is n is even.
         * @throws \Exception
         */
        public function testTraceOfExchangeMatrix()
        {
            foreach (range(start: 1, end: 20) as $n)
            {
                // Given
                $J = MatrixFactory::exchange(n: $n);
                // When
                $tr⟮J⟯ = $J->trace();

                // Then
                if (Integer::isOdd(x: $n))
                    $this->assertEquals(1, $tr⟮J⟯); else
                    $this->assertEquals(0, $tr⟮J⟯);
            }
        }

        /**
         * @test         Axiom: Signature matrix is involutory
         * @dataProvider dataProviderForSignatureMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSignatureMatrixIsInvolutory(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isSignature());
            $this->assertTrue($A->isInvolutory());
        }

        /**
         * @test Axiom: Hilbert matrix is symmetric
         * @throws   \Exception
         */
        public function testHilbertMatrixIsSymmetric()
        {
            foreach (range(start: 1, end: 10) as $n)
            {
                // Given
                $H = MatrixFactory::hilbert(n: $n);
                // Then
                $this->assertTrue($H->isSymmetric());
            }
        }

        /**
         * @test Axiom: Hilbert matrix is positive definite
         * @throws   \Exception
         */
        public function testHilbertMatrixIsPositiveDefinite()
        {
            foreach (range(start: 1, end: 10) as $n)
            {
                // Given
                $H = MatrixFactory::hilbert(n: $n);
                // Then
                $this->assertTrue($H->isPositiveDefinite());
            }
        }

        /**
         * @test         Axiom: A = LLᵀ (Cholesky decomposition)
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCholeskyDecompositionLTimesLTransposeIsA(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $cholesky = $A->choleskyDecomposition();
            $L = $cholesky->L;
            $Lᵀ = $cholesky->LT;

            // When
            $LLᵀ = $L->multiply(B: $Lᵀ);

            // Then
            $this->assertEqualsWithDelta($A->getMatrix(), $LLᵀ->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: L is lower triangular (Cholesky decomposition)
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCholeskyDecompositionLIsLowerTriangular(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $cholesky = $A->choleskyDecomposition();

            // Then
            $this->assertTrue($cholesky->L->isLowerTriangular());
        }

        /**
         * @test         Axiom: Lᵀ is upper triangular (Cholesky decomposition)
         * @dataProvider dataProviderForPositiveDefiniteMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testCholeskyDecompositionLTransposeIsUpperTriangular(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $cholesky = $A->choleskyDecomposition();

            // Then
            $this->assertTrue($cholesky->LT->isUpperTriangular());
        }

        /**
         * @test         Axiom: adj⟮A⟯ = Cᵀ
         *               Adjugate matrix equals the transpose of the cofactor matrix
         * @dataProvider dataProviderForSquareMatrixGreaterThanOne
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testAdjugateIsTransoseOfCofactorMatrix(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $adj⟮A⟯ = $A->adjugate();
            $Cᵀ = $A->cofactorMatrix()->transpose();

            // Then
            $this->assertEqualsWithDelta($adj⟮A⟯, $Cᵀ, 0.00001);
        }

        /**
         * @test         Axiom: A adj⟮A⟯ = det⟮A⟯ I
         *               The product of A with its adjugate yields a diagonal matrix whose diagonal entries are det(A)
         * @dataProvider dataProviderForSquareMatrixGreaterThanOne
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testAdjugateTimesAIsIdentityMatrixTimesDeterminantOfA(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $adj⟮A⟯ = $A->adjugate();
            $Aadj⟮A⟯ = $A->multiply(B: $adj⟮A⟯);

            // And
            $I = MatrixFactory::identity(n: $A->getN());
            $det⟮A⟯ = $A->det();
            $det⟮A⟯I = $I->scalarMultiply(λ: $det⟮A⟯);

            // Then
            $this->assertEqualsWithDelta($Aadj⟮A⟯, $det⟮A⟯I, 0.00001);
        }

        /**
         * @test         Axiom: adj⟮A⟯ = det⟮A⟯A⁻¹
         *               The product of A with its adjugate yields a diagonal matrix whose diagonal entries are det(A)
         * @dataProvider dataProviderForNonsingularMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testAdjugateEqualsInverseOfATimesDeterminant(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();
            $adj⟮A⟯ = $A->adjugate();
            $det⟮A⟯ = $A->det();
            $det⟮A⟯A⁻¹ = $A⁻¹->scalarMultiply(λ: $det⟮A⟯);

            // Then
            $this->assertEqualsWithDelta($adj⟮A⟯, $det⟮A⟯A⁻¹, 0.00001);
        }

        /**
         * @test         Axiom: A⁻¹ = (1/det⟮A⟯) adj⟮A⟯
         *               The inverse of a matrix is equals to one over the determinant multiplied by the adjugate
         * @dataProvider dataProviderForNonsingularMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testInverseEqualsOneOverDetTimesAdjugate(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $A⁻¹ = $A->inverse();
            $adj⟮A⟯ = $A->adjugate();
            $det⟮A⟯ = $A->det();
            $⟮1／det⟮A⟯⟯adj⟮A⟯ = $adj⟮A⟯->scalarMultiply(λ: 1 / $det⟮A⟯);

            // Then
            $this->assertEqualsWithDelta($A⁻¹, $⟮1／det⟮A⟯⟯adj⟮A⟯, 0.00001);
        }

        /**
         * @test         Axiom: adj⟮I⟯ = I
         *               The adjugate of identity matrix is identity matrix
         * @dataProvider dataProviderForIdentityMatrix
         *
         * @param array $I
         *
         * @throws       \Exception
         */
        public function testAdjugateOfIdenetityMatrixIsIdentity(array $I)
        {
            // Given
            $I = MatrixFactory::create(A: $I);

            // When
            $adj⟮I⟯ = $I->adjugate();

            // Then
            $this->assertEqualsWithDelta($adj⟮I⟯, $I, 0.00001);
        }

        /**
         * @test         Axiom: adj⟮AB⟯ = adj⟮B⟯adj⟮A⟯
         *               The adjugate of AB equals the adjugate of B times the adjugate of A
         * @dataProvider dataProviderForTwoNonsingularMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testAdjugateABEqualsAdjugateBTimesAdjugateA(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);

            // When
            $AB = $A->multiply(B: $B);
            $adj⟮A⟯ = $A->adjugate();
            $adj⟮B⟯ = $B->adjugate();
            $adj⟮AB⟯ = $AB->adjugate();
            $adj⟮B⟯adj⟮A⟯ = $adj⟮B⟯->multiply(B: $adj⟮A⟯);

            // Then
            $this->assertEqualsWithDelta($adj⟮AB⟯, $adj⟮B⟯adj⟮A⟯, 0.00001);
        }

        /**
         * @test         Axiom: adj⟮cA⟯ = cⁿ⁻¹ adj⟮A⟯
         *               The adjugate of a matrix times a scalar equals the adjugate of the matrix then times a scalar raised to n - 1
         * @dataProvider dataProviderForNonsingularMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testAdjugateAtimesCEqualsAdjugateATimesCRaisedToNMinusOne(
            array $A
        ) {
            // Given
            $c = 4;
            $A = MatrixFactory::create(A: $A);

            // When
            $cA = $A->scalarMultiply(λ: $c);
            $adj⟮A⟯ = $A->adjugate();
            $adj⟮cA⟯ = $cA->adjugate();
            $cⁿ⁻¹ = $c ** ($A->getN() - 1);
            $cⁿ⁻¹adj⟮A⟯ = $adj⟮A⟯->scalarMultiply(λ: $cⁿ⁻¹);

            // Then
            $this->assertEqualsWithDelta($adj⟮cA⟯, $cⁿ⁻¹adj⟮A⟯, 0.00001);
        }

        /**
         * @test         Axiom: adj⟮B⟯adj⟮A⟯ = det⟮B⟯B⁻¹ det⟮A⟯A⁻¹ = det⟮AB⟯⟮AB⟯⁻¹
         *               The adjugate of B times adjugate A equals the determinant of B times inverse of B times determinant of A times inverse of A
         *               which equals the determinant of AB times the inverse of AB
         * @dataProvider dataProviderForTwoNonsingularMatrices
         *
         * @param array $A
         * @param array $B
         *
         * @throws       \Exception
         */
        public function testAdjugateBTimesAdjugateAEqualsDetBTimesInverseBTimesDetATimesInverseAEqualsDetABTimesInverseAB(
            array $A,
            array $B
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $B = MatrixFactory::create(A: $B);
            $A⁻¹ = $A->inverse();
            $B⁻¹ = $B->inverse();
            $AB = $A->multiply(B: $B);
            $⟮AB⟯⁻¹ = $AB->inverse();
            $adj⟮A⟯ = $A->adjugate();
            $adj⟮B⟯ = $B->adjugate();
            $det⟮A⟯ = $A->det();
            $det⟮B⟯ = $B->det();
            $det⟮AB⟯ = $AB->det();

            // When
            $det⟮A⟯A⁻¹ = $A⁻¹->scalarMultiply(λ: $det⟮A⟯);
            $det⟮B⟯B⁻¹ = $B⁻¹->scalarMultiply(λ: $det⟮B⟯);

            // And
            $adj⟮B⟯adj⟮A⟯ = $adj⟮B⟯->multiply(B: $adj⟮A⟯);
            $det⟮B⟯B⁻¹det⟮A⟯A⁻¹ = $det⟮B⟯B⁻¹->multiply(B: $det⟮A⟯A⁻¹);
            $det⟮AB⟯⟮AB⟯⁻¹ = $⟮AB⟯⁻¹->scalarMultiply(λ: $det⟮AB⟯);

            // Then
            $this->assertEqualsWithDelta($adj⟮B⟯adj⟮A⟯, $det⟮B⟯B⁻¹det⟮A⟯A⁻¹,
                0.001);
            $this->assertEqualsWithDelta($det⟮B⟯B⁻¹det⟮A⟯A⁻¹, $det⟮AB⟯⟮AB⟯⁻¹,
                0.001);
            $this->assertEqualsWithDelta($adj⟮B⟯adj⟮A⟯, $det⟮AB⟯⟮AB⟯⁻¹, 0.001);
        }

        /**
         * @test         Axiom: adj⟮Aᵀ⟯ = adj⟮A⟯ᵀ
         *               The adjugate of a matrix transpase equals the transpose of a matrix adjugate
         * @dataProvider dataProviderForNonsingularMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testAdjugateOfTransposeEqualsTransposeOfAdjugate(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();
            $adj⟮A⟯ = $A->adjugate();
            $adj⟮Aᵀ⟯ = $Aᵀ->adjugate();
            $adj⟮A⟯ᵀ = $adj⟮A⟯->transpose();

            // Then
            $this->assertEqualsWithDelta($adj⟮Aᵀ⟯, $adj⟮A⟯ᵀ, 0.00001);
        }

        /**
         * @test         Axiom: Aadj⟮A⟯ = adj⟮A⟯A = det⟮A⟯I
         *               A matrix times its adjugate equals the adjugate times the matrix which equals the identity matrix times the determinant
         * @dataProvider dataProviderForNonsingularMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testMatrixTimesItsAdjugateEqualsAdjugateTimesMatrixEqualsDetTimesIdentity(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $adj⟮A⟯ = $A->adjugate();
            $Aadj⟮A⟯ = $A->multiply(B: $adj⟮A⟯);
            $adj⟮A⟯A = $adj⟮A⟯->multiply(B: $A);
            $det⟮A⟯ = $A->det();
            $I = MatrixFactory::identity(n: $A->getN());
            $det⟮A⟯I = $I->scalarMultiply(λ: $det⟮A⟯);

            // Then
            $this->assertEqualsWithDelta($Aadj⟮A⟯, $adj⟮A⟯A, 0.0001);
            $this->assertEqualsWithDelta($Aadj⟮A⟯, $det⟮A⟯I, 0.0001);
            $this->assertEqualsWithDelta($adj⟮A⟯A, $det⟮A⟯I, 0.0001);
        }

        /**
         * @test         Axiom: rank(A) ≤ min(m, n)
         *               The rank of a matrix is less than or equal to the minimum dimension of the matrix
         * @dataProvider dataProviderForSingleMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testRankLessThanMinDimension(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertLessThanOrEqual(min($A->getM(), $A->getN()),
                $A->rank());
        }

        /**
         * @test Axiom: Zero matrix has rank of 0
         * @throws   \Exception
         */
        public function testZeroMatrixHasRankOfZero()
        {
            foreach (range(start: 1, end: 10) as $m)
                foreach (range(1, 10) as $n)
                {
                    // Given
                    $A = MatrixFactory::zero($m, $n);
                    // Then
                    $this->assertEquals(0, $A->rank());
                }
        }

        /**
         * @test         Axiom: If A is square matrix, then it is invertible only if rank = n (full rank)
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testSquareMatrixInvertibleIfFullRank(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $rank = $A->rank();

            // Then
            if ($rank === $A->getM())
                $this->assertTrue($A->isInvertible()); else
                $this->assertFalse($A->isInvertible());
        }

        /**
         * @test         Axiom: rank(AᵀA) = rank(AAᵀ) = rank(A) = rank(Aᵀ)
         * @dataProvider dataProviderForSingleMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testRankTransposeEqualities(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);
            $Aᵀ = $A->transpose();
            $AᵀA = $Aᵀ->multiply(B: $A);
            $AAᵀ = $A->multiply(B: $Aᵀ);

            // When
            $rank⟮A⟯ = $A->rank();
            $rank⟮Aᵀ⟯ = $Aᵀ->rank();
            $rank⟮AᵀA⟯ = $AᵀA->rank();
            $rank⟮AAᵀ⟯ = $AAᵀ->rank();

            // Then
            $this->assertEquals($rank⟮A⟯, $rank⟮Aᵀ⟯);
            $this->assertEquals($rank⟮A⟯, $rank⟮AᵀA⟯);
            $this->assertEquals($rank⟮A⟯, $rank⟮AAᵀ⟯);
            $this->assertEquals($rank⟮Aᵀ⟯, $rank⟮AᵀA⟯);
            $this->assertEquals($rank⟮Aᵀ⟯, $rank⟮AAᵀ⟯);
            $this->assertEquals($rank⟮AᵀA⟯, $rank⟮AAᵀ⟯);
        }

        /**
         * @test         Axiom: Lower bidiagonal matrix is upper Hessenberg
         * @dataProvider dataProviderForLowerBidiagonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testLowerBidiagonalMatrixIsUpperHessenberg(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isLowerBidiagonal());
            $this->assertTrue($A->isUpperHessenberg());
        }

        /**
         * @test         Axiom: Upper bidiagonal matrix is lower Hessenberg
         * @dataProvider dataProviderForUpperBidiagonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testUpperBidiagonalMatrixIsLowerHessenberg(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isUpperBidiagonal());
            $this->assertTrue($A->isLowerHessenberg());
        }

        /**
         * @test         Axiom: A matrix that is both upper Hessenberg and lower Hessenberg is a tridiagonal matrix
         * @dataProvider dataProviderForTridiagonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testTridiagonalMatrixIsUpperAndLowerHessenberg(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // Then
            $this->assertTrue($A->isTridiagonal());
            $this->assertTrue($A->isUpperHessenberg());
            $this->assertTrue($A->isLowerHessenberg());
        }

        /**
         * @test         Axiom: AAᵀ = I for an orthogonal matrix A
         * @dataProvider dataProviderForOrthogonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testOrthogonalMatrixTimesTransposeIsIdentityMatrix(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $Aᵀ = $A->transpose();
            $I = MatrixFactory::identity(n: $A->getM());

            // When
            $AAᵀ = $A->multiply(B: $Aᵀ);

            // Then
            $this->assertEqualsWithDelta($I->getMatrix(), $AAᵀ->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: AᵀA = I for an orthogonal matrix A
         * @dataProvider dataProviderForOrthogonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testOrthogonalTransposeOfOrthogonalMatrixTimesMatrixIsIdentityMatrix(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $Aᵀ = $A->transpose();
            $I = MatrixFactory::identity(n: $A->getM());

            // When
            $AᵀA = $Aᵀ->multiply(B: $A);

            // Then
            $this->assertEqualsWithDelta($I->getMatrix(), $AᵀA->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: A⁻¹ = Aᵀ for an orthogonal matrix A
         * @dataProvider dataProviderForOrthogonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testOrthogonalMatrixInverseEqualsTransposeOfOrthogonalMatrix(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $Aᵀ = $A->transpose();
            $A⁻¹ = $A->inverse();

            // Then
            $this->assertEqualsWithDelta($A⁻¹->getMatrix(), $Aᵀ->getMatrix(),
                0.00001);
        }

        /**
         * @test         Axiom: det(A) = ±1 for an orthogonal matrix A
         * @dataProvider dataProviderForOrthogonalMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testOrthogonalMatrixDeterminateIsOne(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $det⟮A⟯ = $A->det();

            // Then
            $this->assertEqualsWithDelta(1, abs(num: $det⟮A⟯), 0.000001);
        }

        /**
         * @test         Axiom: Householder transformation creates a matrix that is involutory
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testHouseholderTransformMatrixInvolutoryProperty(
            array $A
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $H = $A->householder();

            // Then
            $this->assertTrue($H->isInvolutory());
        }

        /**
         * @test         Axiom: Householder transformation creates a matrix with a determinant that is -1
         * @dataProvider dataProviderForSquareMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testHouseholderTransformMatrixDeterminant(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $H = $A->householder();

            // Then
            $this->assertEqualsWithDelta(-1, $H->det(), 0.000001);
        }

        /**
         * @test         Axiom: Householder transformation creates a matrix that has eigenvalues 1 and -1
         * @dataProvider dataProviderForSquareMatrixGreaterThanOne
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testHouseholderTransformMatrixEigenvalues(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $H = $A->householder();

            // Then
            $array_filter = array_filter($H->eigenvalues(), function ($x) {
                return ! is_nan($x);
            });
            $eigenvalues = $array_filter;
            $this->assertEqualsWithDelta(1, max(value: $eigenvalues), 0.00001);
            $this->assertEqualsWithDelta(-1, min(value: $eigenvalues), 0.00001);
        }

        /**
         * @test         Axiom: Nilpotent matrix - tr(Aᵏ) = 0 for all k > 0
         * @dataProvider dataProviderForNilpotentMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testNilpotentTraceIsZero(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            foreach (range(start: 1, end: 5) as $ignored)
            {
                // When
                $A = $A->multiply(B: $A);
                $trace = $A->trace();

                // Then
                $this->assertEquals(0, $trace);
            }
        }

        /**
         * @test         Axiom: Nilpotent matrix - det = 0
         * @dataProvider dataProviderForNilpotentMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testNilpotentDetIsZero(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $det = $A->det();

            // Then
            $this->assertEquals(0, $det);
        }

        /**
         * @test         Axiom: Nilpotent matrix - Cannot be invertible
         * @dataProvider dataProviderForNilpotentMatrix
         *
         * @param array $A
         *
         * @throws       \Exception
         */
        public function testNilpotentCannotBeInvertible(array $A)
        {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $isInvertible = $A->isInvertible();

            // Then
            $this->assertFalse($isInvertible);
        }
    }
