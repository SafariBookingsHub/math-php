<?php

    namespace MathPHP\LinearAlgebra;

    use JetBrains\PhpStorm\Pure;
    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\Functions\Map\Single;

    use function print_r;

    /**
     * Diagonal matrix
     * Elements along the main diagonal are the only non-zero elements (may also be zero).
     * The off-diagonal elements are all zero
     */
    class NumericDiagonalMatrix extends NumericSquareMatrix {
        /**
         * Constructor
         *
         * @param array<array<int|float>> $A
         *
         * @throws \MathPHP\Exception\MatrixException
         */
        public function __construct(array $A)
        {
            try
            {
                parent::__construct($A);
            } catch (MathException $e)
            {
            }

            if ( ! parent::isLowerTriangular() || ! parent::isUpperTriangular())
            {
                {
                    throw new MatrixException('Trying to construct DiagonalMatrix with non-diagonal elements: '
                        .print_r($this->A, TRUE));
                }
            }
        }

        /**
         * Diagonal matrix must be lower triangular
         *
         * @inheritDoc
         */
        #[Pure] public function isLowerTriangular(): bool
        {
            return TRUE;
        }

        /**
         * Diagonal matrix must be upper triangular
         *
         * @inheritDoc
         */
        #[Pure] public function isUpperTriangular(): bool
        {
            return TRUE;
        }

        /**
         * Diagonal matrix must be symmetric
         *
         * @inheritDoc
         */
        #[Pure] public function isSymmetric(): bool
        {
            return TRUE;
        }

        /**
         * Diagonal matrix must be triangular
         *
         * @inheritDoc
         */
        public function isTriangular(): bool
        {
            return TRUE;
        }

        /**
         * Diagonal matrix must be diagonal
         *
         * @inheritDoc
         */
        public function isDiagonal(): bool
        {
            return TRUE;
        }

        /**
         * Inverse of a diagonal matrix is the reciprocals of the diagonal elements
         *
         * @return NumericMatrix
         */
        public function inverse(): NumericMatrix
        {
            try
            {
                return MatrixFactory::diagonal(Single::reciprocal($this->getDiagonalElements()));
            } catch (BadDataException|MatrixException $e)
            {
            }
        }
    }
