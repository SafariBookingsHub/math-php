<?php

    namespace MathPHP\LinearAlgebra;

    use MathPHP\Exception;
    use MathPHP\Number\ArbitraryInteger;
    use MathPHP\Number\ObjectArithmetic;

    use function array_map;
    use function array_reduce;
    use function count;

    /**
     * ObjectMatrix
     *
     * The ObjectMatrix extends Matrix functions to a matrix of objects.
     * The object must implement the MatrixArithmetic interface to prove
     * compatibility. It extends the SquareMatrix in order to use Matrix::minor().
     *
     * @extends Matrix<ObjectArithmetic>
     */
    class ObjectMatrix extends Matrix implements ObjectArithmetic {
        /**
         * The type of object that is being stored in this Matrix
         *
         * @var string
         */
        protected string $object_type;

        /**
         * The constructor follows performs all the same checks as the parent, but also checks that
         * all of the elements in the array are of the same data type.
         *
         * @param ObjectArithmetic[][] $A m x n matrix of objects
         *
         * @throws Exception\BadDataException if any rows have a different column count
         * @throws Exception\IncorrectTypeException if all elements are not the same class
         * @throws Exception\IncorrectTypeException if The class does not implement the ObjectArithmetic interface
         * @throws Exception\MathException
         */
        public function __construct(array $A)
        {
            $this->A = $A;
            $this->m = count($A);
            $this->n = ($this->m > 0) ? count($A[0]) : 0;
            $this->catalog = new MatrixCatalog();

            $this->validateMatrixData();
        }

        /**
         * Validate the matrix is entirely m x n
         *
         * @throws Exception\BadDataException if any rows have a different column count
         * @throws Exception\IncorrectTypeException if all elements are not the same class
         * @throws Exception\IncorrectTypeException if The class does not implement the ObjectArithmetic interface
         * @throws Exception\MathException
         */
        protected function validateMatrixData(): void
        {
            if ($this->A[0][0] instanceof ObjectArithmetic)
            {
                $this->object_type = $this->A[0][0]::class;
            } else
            {
                throw new Exception\IncorrectTypeException("The object must implement the interface.");
            }
            foreach ($this->A as $i => $row)
            {
                foreach ($row as $object)
                {
                    if ($object::class != $this->object_type)
                    {
                        throw new Exception\IncorrectTypeException("All elements in the matrix must be of the same type.");
                    }
                }
            }
            foreach ($this->A as $i => $row)
            {
                if (count($row) !== $this->n)
                {
                    throw new Exception\BadDataException("Row $i has a different column count: "
                        .count($row)."; was expecting {$this->n}.");
                }
            }
        }

        public static function traceNotSquare()
        {
        }

        public static function scalarMultiplyByObject()
        {
        }

        /***************************************************************************
         * MATRIX COMPARISONS
         *  - isEqual
         ***************************************************************************/

        public static function matrixDetException()
        {
        }

        /**
         * Is this matrix equal to some other matrix?
         *
         * @param Matrix<ObjectArithmetic> $B
         *
         * @return bool
         */
        public function isEqual(Matrix $B): bool
        {
            if ( ! $this->isEqualSizeAndType($B))
            {
                return FALSE;
            }

            $m = $this->m;
            $n = $this->n;
            // All elements are the same
            for ($i = 0; $i < $m; $i++)
            {
                for ($j = 0; $j < $n; $j++)
                {
                    if ($this->A[$i][$j] != $B[$i][$j])
                    {
                        return FALSE;
                    }
                }
            }

            return TRUE;
        }

        /**************************************************************************
         * MATRIX ARITHMETIC OPERATIONS - Return a Matrix
         *  - add
         *  - subtract
         *  - multiply
         *  - scalarMultiply
         **************************************************************************/

        /**
         * {@inheritDoc}
         * @param mixed $object_or_scalar
         *
         * @return \MathPHP\Number\ObjectArithmetic
         * @throws \MathPHP\Exception\IncorrectTypeException
         */
        public function subtract(mixed $object_or_scalar): ObjectArithmetic
        {
            if ( ! ($object_or_scalar instanceof Matrix))
            {
                throw new Exception\IncorrectTypeException('Can only do matrix subtraction with a Matrix');
            }
            try
            {
                $this->checkEqualSizes($object_or_scalar);
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }
            $R = [];
            for ($i = 0; $i < $this->m; $i++)
            {
                for ($j = 0; $j < $this->n; $j++)
                {
                    $R[$i][$j]
                        = $this->A[$i][$j]->subtract($object_or_scalar[$i][$j]);
                }
            }

            /** @var ObjectMatrix $R */
            try
            {
                return MatrixFactory::create($R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * Check that the matrices are the same size and of the same type
         *
         * @param Matrix<mixed> $B
         *
         * @throws Exception\MatrixException if matrices have a different number of rows or columns
         * @throws Exception\IncorrectTypeException if the two matricies are not the same class
         */
        private function checkEqualSizes(Matrix $B): void
        {
            if (($B->getM() !== $this->m) || ($B->getN() !== $this->n))
            {
                throw new Exception\MatrixException('Matrices are different sizes');
            }
            if ($B->getObjectType() !== $this->object_type)
            {
                throw new Exception\IncorrectTypeException('Matrices must contain the same object types');
            }
        }

        /**
         * Get the type of objects that are stored in the matrix
         *
         * @return string The class of the objects
         */
        public function getObjectType(): string
        {
            return $this->object_type;
        }

        /**
         * Scalar matrix multiplication
         * https://en.wikipedia.org/wiki/Matrix_multiplication#Scalar_multiplication
         *
         * @param float $λ
         *
         * @return ObjectMatrix
         *
         */
        public function scalarMultiply(float $λ): Matrix
        {
            $R = [];

            for ($i = 0; $i < $this->m; $i++)
            {
                for ($j = 0; $j < $this->n; $j++)
                {
                    $R[$i][$j] = $this->A[$i][$j]->multiply($λ);
                }
            }

            /** @var ObjectMatrix $R */
            try
            {
                return MatrixFactory::create($R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**************************************************************************
         * MATRIX OPERATIONS - Return a value
         *  - trace
         *  - det
         *  - cofactor
         **************************************************************************/

        /**
         * {@inheritDoc}
         * @param mixed $object_or_scalar
         *
         * @return \MathPHP\Number\ObjectArithmetic
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\MatrixException
         */
        public function multiply(mixed $object_or_scalar): ObjectArithmetic
        {
            if ( ! ($object_or_scalar instanceof Matrix)
                && ! ($object_or_scalar instanceof Vector)
            )
            {
                throw new Exception\IncorrectTypeException('Can only do matrix multiplication with a Matrix or Vector');
            }
            if ($object_or_scalar instanceof Vector)
            {
                $object_or_scalar = $object_or_scalar->asColumnMatrix();
            }
            if ($object_or_scalar->getM() !== $this->n)
            {
                throw new Exception\MatrixException("Matrix dimensions do not match");
            }
            $n = $object_or_scalar->getN();
            $m = $this->m;
            $R = [];
            for ($i = 0; $i < $m; $i++)
            {
                for ($j = 0; $j < $n; $j++)
                {
                    /** @var array<ObjectArithmetic> $VA */
                    $VA = $this->getRow($i);
                    /** @var array<ObjectArithmetic> $VB */
                    $VB = $object_or_scalar->getColumn($j);
                    $R[$i][$j] = array_reduce(
                        array_map(
                            fn(
                                ObjectArithmetic $a,
                                ObjectArithmetic $b
                            ) => $a->multiply($b),
                            $VA,
                            $VB
                        ),
                        fn($sum, $item) => $sum
                            ? $sum->add($item)
                            : $item
                    );
                }
            }

            /** @var ObjectMatrix $R */
            try
            {
                return MatrixFactory::create($R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * {@inheritDoc}
         * @param mixed $object_or_scalar
         *
         * @return \MathPHP\Number\ObjectArithmetic
         * @throws \MathPHP\Exception\IncorrectTypeException
         */
        public function add(mixed $object_or_scalar): ObjectArithmetic
        {
            if ( ! ($object_or_scalar instanceof Matrix))
            {
                throw new Exception\IncorrectTypeException('Can only do matrix addition with a Matrix');
            }
            try
            {
                $this->checkEqualSizes($object_or_scalar);
            } catch (Exception\IncorrectTypeException|Exception\MatrixException $e)
            {
            }
            $R = [];
            for ($i = 0; $i < $this->m; $i++)
            {
                for ($j = 0; $j < $this->n; $j++)
                {
                    $R[$i][$j]
                        = $this->A[$i][$j]->add($object_or_scalar[$i][$j]);
                }
            }

            /** @var ObjectMatrix $R */
            try
            {
                return MatrixFactory::create($R);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * {}
         *
         * @return int|float
         *
         * @throws Exception\MatrixException if the matrix is not a square matrix
         */
        public function trace(): float|int
        {
            if ( ! $this->isSquare())
            {
                throw new Exception\MatrixException('trace only works on a square matrix');
            }

            $m = $this->m;
            $tr⟮A⟯ = $this->getObjectType()::createZeroValue();

            for ($i = 0; $i < $m; $i++)
            {
                $tr⟮A⟯ = $tr⟮A⟯->add($this->A[$i][$i]);
            }

            return $tr⟮A⟯;
        }

        /**
         * Zero value: [[0]]
         *
         * @return ObjectMatrix
         */
        public static function createZeroValue(): ObjectArithmetic
        {
            try
            {
                return new ObjectMatrix([[new ArbitraryInteger(0)]]);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException|Exception\BadParameterException $e)
            {
            }
        }

        /**
         * Determinant
         *
         * This implementation is simpler than that of the parent. Instead of
         * reducing the matrix, which requires division, we calculate the cofactors
         * for the first row of the matrix, perform element-wise multiplication, and
         * add the results of that row.
         *
         * This implementation also uses the same algorithm for 2x2 matrices. Adding
         * a special case may quicken code execution.
         *
         * @return ObjectArithmetic
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\IncorrectTypeException
         * @throws \MathPHP\Exception\MatrixException
         */
        public function det(): ObjectArithmetic
        {
            if ($this->catalog->hasDeterminant())
            {
                /** @var ObjectArithmetic */
                return $this->catalog->getDeterminant();
            }

            if ( ! $this->isSquare())
            {
                throw new Exception\MatrixException('Not a square matrix (required for determinant)');
            }

            $m = $this->m;
            /** @var ObjectMatrix $R */
            try
            {
                $R = MatrixFactory::create($this->A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            /*
             * 1x1 matrix
             *  A = [a]
             *
             * |A| = a
             */
            if ($m === 1)
            {
                {
                    $det = $R[0][0];
                }
            } else
            {
                // Calculate the cofactors of the top row of the matrix
                $row_of_cofactors = [];
                for ($i = 0; $i < $m; $i++)
                {
                    $row_of_cofactors[$i] = $R->cofactor(0, $i);
                }

                // Since we don't know what the data type is, we can't initialze $det
                // to zero without a special initialize() or zero() method.
                $initialize = TRUE;
                $det = $R[0][0]->multiply($row_of_cofactors[0]);
                foreach ($row_of_cofactors as $key => $value)
                {
                    if ($initialize)
                    {
                        {
                            $initialize = FALSE;
                        }
                    } else
                    {
                        $det = $det->add($R[0][$key]->multiply($value));
                    }
                }
            }

            $this->catalog->addDeterminant($det);

            return $det;
        }

        public function cofactor(int $mᵢ, int $nⱼ): ObjectArithmetic
        {
            /** @var ObjectArithmetic $Mᵢⱼ */
            try
            {
                $Mᵢⱼ = $this->minor($mᵢ, $nⱼ);
            } catch (Exception\BadParameterException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }
            $⟮−1⟯ⁱ⁺ʲ = (-1) ** ($mᵢ + $nⱼ);

            return $Mᵢⱼ->multiply($⟮−1⟯ⁱ⁺ʲ);
        }

        public function transpose(): Matrix
        {
        }

        public function multiplyVector()
        {
        }

        public function mul()
        {
        }

        public function matrixMultiplyException()
        {
        }

        public function matrixSubtractException()
        {
        }

        public function matrixAddException()
        {
        }

        public function matrixConstructorException()
        {
        }
    }
