<?php

    namespace MathPHP\LinearAlgebra\Decomposition;

    use MathPHP\Exception;
    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\DivisionByZeroException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\LinearAlgebra\Vector;

    use function is_array;

    use const NAN;

    /**
     * LU Decomposition (Doolittle decomposition) with partial pivoting via permutation matrix
     *
     * A matrix has an LU-factorization if it can be expressed as the product of a
     * lower-triangular matrix L and an upper-triangular matrix U. If A is a nonsingular
     * matrix, then we can find a permutation matrix P so that PA will have an LU decomposition:
     *   PA = LU
     *
     * https://en.wikipedia.org/wiki/LU_decomposition
     * https://en.wikipedia.org/wiki/LU_decomposition#Doolittle_algorithm
     *
     * L: Lower triangular matrix--all entries above the main diagonal are zero.
     *    The main diagonal will be all ones.
     * U: Upper triangular matrix--all entries below the main diagonal are zero.
     * P: Permutation matrix--Identity matrix with possible rows interchanged.
     *
     * Example:
     *      [1 3 5]
     *  A = [2 4 7]
     *      [1 1 0]
     *
     * Create permutation matrix P:
     *      [0 1 0]
     *  P = [1 0 0]
     *      [0 0 1]
     *
     * Pivot A to be PA:
     *       [0 1 0][1 3 5]   [2 4 7]
     *  PA = [1 0 0][2 4 7] = [1 3 5]
     *       [0 0 1][1 1 0]   [1 1 0]
     *
     * Calculate L and U
     *
     *     [1    0 0]      [2 4   7]
     * L = [0.5  1 0]  U = [0 1 1.5]
     *     [0.5 -1 1]      [0 0  -2]
     *
     * @property-read NumericMatrix $L Lower triangular matrix in LUP decomposition
     * @property-read NumericMatrix $U Upper triangular matrix in LUP decomposition
     * @property-read NumericMatrix $P Permutation matrix in LUP decomposition
     */
    class LU extends Decomposition {

        /**
         * LU constructor
         *
         * @param NumericMatrix $L Lower triangular matrix
         * @param NumericMatrix $U Upper triangular matrix
         * @param NumericMatrix $P Permutation matrix
         */
        private function __construct(
            private NumericMatrix $L,
            private NumericMatrix $U,
            private NumericMatrix $P
        ) {
        }

        /**
         * Decompose a matrix into an LU Decomposition (using Doolittle decomposition) with pivoting via permutation matrix
         * Factory method to create LU objects.
         *
         * @param NumericMatrix $A
         *
         * @return \MathPHP\LinearAlgebra\Decomposition\Decomposition
         *
         * @throws Exception\BadDataException
         * @throws Exception\IncorrectTypeException
         * @throws Exception\MathException
         * @throws Exception\MatrixException
         * @throws Exception\OutOfBoundsException
         * @throws Exception\VectorException
         */
        public static function decompose(NumericMatrix $A): Decomposition
        {
            if ( ! $A->isSquare())
            {
                throw new Exception\MatrixException('LU decomposition only works on square matrices');
            }

            $n = $A->getN();

            // Initialize L as diagonal ones matrix, and U as zero matrix
            /** @var array<int> $fill */
            $array_fill = array_fill(0, $n - 0, 1);
            $fill = $array_fill;
            $L = MatrixFactory::diagonal($fill)->getMatrix();
            $U = MatrixFactory::zero($n, $n)->getMatrix();

            // Create permutation matrix P and pivoted PA
            $P = self::pivotize($A);
            $PA = $P->multiply($A);

            // Fill out L and U
            for ($i = 0; $i < $n; $i++)
            {
                // Calculate Uⱼᵢ
                for ($j = 0; $j <= $i; $j++)
                {
                    $sum = 0;
                    for ($k = 0; $k < $j; $k++)
                    {
                        $sum += $U[$k][$i] * $L[$j][$k];
                    }
                    $U[$j][$i] = $PA[$j][$i] - $sum;
                }

                // Calculate Lⱼᵢ
                for ($j = $i; $j < $n; $j++)
                {
                    $sum = 0;
                    for ($k = 0; $k < $i; $k++)
                    {
                        $sum += $U[$k][$i] * $L[$j][$k];
                    }
                    $L[$j][$i] = ($U[$i][$i] == 0) ? NAN
                        : (($PA[$j][$i] - $sum) / $U[$i][$i]);
                }
            }

            // Create LU decomposition @phpstan-ignore-next-line
            return new LU(MatrixFactory::create($L), MatrixFactory::create($U),
                $P);
        }

        /**
         * Pivotize creates the permutation matrix P for the LU decomposition using partial pivoting.
         * The permutation matrix is an identity matrix with rows possibly interchanged.
         *
         * The product PA results in a new matrix whose rows consist of the rows of A
         * but no rearranged in the order specified by the permutation matrix P.
         *
         * Example:
         *
         *     [α₁₁ α₁₂ α₁₃]
         * A = [α₂₁ α₂₂ α₂₃]
         *     [α₃₁ α₃₂ α₃₃]
         *
         *     [0 1 0]
         * P = [1 0 0]
         *     [0 0 1]
         *
         *      [α₂₁ α₂₂ α₂₃] \ rows
         * PA = [α₁₁ α₁₂ α₁₃] / interchanged
         *      [α₃₁ α₃₂ α₃₃]
         *
         * @param \MathPHP\LinearAlgebra\NumericMatrix $A
         *
         * @return NumericMatrix
         *
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\MatrixException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        protected static function pivotize(NumericMatrix $A): NumericMatrix
        {
            $n = $A->getN();
            $P = MatrixFactory::identity($n);

            // Set initial column max to diagonal element Aᵢᵢ
            for ($i = 0; $i < $n; $i++)
            {
                $max = $A[$i][$i];
                $row = $i;

                // Check for column element below Aᵢᵢ that is bigger
                for ($j = $i; $j < $n; $j++)
                {
                    if ($A[$j][$i] > $max)
                    {
                        $max = $A[$j][$i];
                        $row = $j;
                    }
                }

                // Swap rows if a larger column element below Aᵢᵢ was found
                if ($i != $row)
                {
                    $P = $P->rowInterchange($i, $row);
                }
            }

            return $P;
        }

        public static function LUDecompositionSolveIncorrectTypeError()
        {
        }

        public static function LUDecompositionInvalidProperty()
        {
        }

        public static function LUDecompositionExceptionNotSquare()
        {
        }

        public static function luDecompositionSmallPivots()
        {
        }

        public static function LUDecompositionLAndUProperties()
        {
        }

        public static function LUDecompositionPaEqualsLu()
        {
        }

        public static function LUDecomposition()
        {
        }

        /**
         * Solve linear system of equations
         * Ax = b
         *  where:
         *   A: Matrix
         *   x: unknown to solve for
         *   b: solution to linear system of equations (input to function)
         *
         * Use LU Decomposition and solve Ax = b.
         *
         * LU Decomposition:
         *  - Equation to solve: Ax = b
         *  - LU Decomposition produces: PA = LU
         *  - Substitute: LUx = Pb, or Pb = LUx
         *  - Can rewrite as Pb = L(Ux)
         *  - Can say y = Ux
         *  - Then can rewrite as Pb = Ly
         *  - Solve for y (we know Pb and L)
         *  - Solve for x in y = Ux once we know y
         *
         * Solving triangular systems Ly = Pb and Ux = y
         *  - Solve for Ly = Pb using forward substitution
         *
         *         1   /    ᵢ₋₁      \
         *   yᵢ = --- | bᵢ - ∑ Lᵢⱼyⱼ |
         *        Lᵢᵢ  \    ʲ⁼¹      /
         *
         *  - Solve for Ux = y using back substitution
         *
         *         1   /     m       \
         *   xᵢ = --- | yᵢ - ∑ Uᵢⱼxⱼ |
         *        Uᵢᵢ  \   ʲ⁼ⁱ⁺¹     /
         *
         * @param array<int|float>|Vector $b solution to Ax = b
         *
         * @return Vector x
         *
         * @throws BadDataException
         * @throws DivisionByZeroException
         * @throws IncorrectTypeException if b is not a Vector or array
         * @throws MathException
         * @throws MatrixException
         */
        public function solve($b): Vector
        {
            if ( ! ($b instanceof Vector) && ! is_array($b))
            {
                throw new Exception\IncorrectTypeException('b in Ax = b must be a Vector or array');
            }

            $b = $b instanceof Vector ? $b : new Vector($b);
            $L = $this->L;
            $U = $this->U;
            $P = $this->P;
            $m = $L->getM();

            // Pivot solution vector b with permutation matrix: Pb
            $Pb = $P->multiply($b);

            // Solve for Ly = Pb using forward substitution
            $y = $this->forwardSubstitution($L, $Pb, $m);

            // Solve for Ux = y using back substitution
            $x = $this->backSubstitution($U, $y, $m);

            return new Vector($x);
        }

        private function forwardSubstitution($L, $Pb, $m): array
        {
            $y = [];
            $y[0] = $Pb[0][0] / $L[0][0];

            for ($i = 1; $i < $m; $i++)
            {
                $y[$i] = ($Pb[$i][0] - array_sum(array_map(function ($j) use (
                            $L,
                            $y
                        ) {
                            return $L[$i][$j] * $y[$j];
                        },
                            range(0, $i - 1)))) / $L[$i][$i];
            }

            return $y;
        }

        private function backSubstitution($U, $y, $m): array
        {
            $x = [];
            $x[$m - 1] = $y[$m - 1] / $U[$m - 1][$m - 1];

            for ($i = $m - 2; $i >= 0; $i--)
            {
                if ($U[$i][$i] == 0)
                {
                    throw new Exception\DivisionByZeroException("Uᵢᵢ (U[$i][$i]) is 0 during back substitution");
                }
                $x[$i] = ($y[$i] - array_sum(array_map(function ($j) use (
                            $U,
                            $x
                        ) {
                            return $U[$i][$j] * $x[$j];
                        },
                            range($i + 1, $m - 1)))) / $U[$i][$i];
            }

            return $x;
        }

        /**
         * Get L, U, or P matrix
         *
         * @param string $name
         *
         * @return NumericMatrix
         *
         * @throws Exception\MatrixException
         */
        public function __get(string $name): NumericMatrix
        {
            switch ($name)
            {
                case 'L':
                case 'U':
                case 'P':
                    return $this->$name;
                default:
                    return throw new Exception\MatrixException("LU class does not have a gettable property: $name");
            }
        }
    }
