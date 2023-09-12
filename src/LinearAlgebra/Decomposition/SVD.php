<?php

    namespace MathPHP\LinearAlgebra\Decomposition;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use MathPHP\LinearAlgebra\Vector;

    /**
     * Singular value decomposition
     *
     * The generalization of the eigendecomposition of a square matrix to an m x n matrix
     * https://en.wikipedia.org/wiki/Singular_value_decomposition
     *
     * @property-read NumericMatrix $S m x n diagonal matrix
     * @property-read NumericMatrix $V n x n orthogonal matrix
     * @property-read NumericMatrix $U m x m orthogonal matrix
     * @property-read Vector        $D diagonal elements from S
     */
    class SVD extends Decomposition {

        /** @var Vector diagonal elements from S that are the singular values */
        private Vector $D;

        /**
         * @param NumericMatrix $U Orthogonal matrix
         * @param NumericMatrix $S Rectangular Diagonal matrix
         * @param NumericMatrix $V Orthogonal matrix
         */
        private function __construct(
            private NumericMatrix $U,
            private NumericMatrix $S,
            private NumericMatrix $V
        ) {
            try
            {
                $this->D = new Vector($S->getDiagonalElements());
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * Generate the Singlue Value Decomposition of the matrix
         *
         * @param NumericMatrix $M
         *
         * @return \MathPHP\LinearAlgebra\Decomposition\Decomposition
         */
        public static function decompose(NumericMatrix $M): Decomposition
        {
            $Mᵀ = $M->transpose();
            try
            {
                $MMᵀ = $M->multiply($Mᵀ);
            } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
            {
            }
            try
            {
                $MᵀM = $Mᵀ->multiply($M);
            } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
            {
            }

            // m x m orthoganol matrix
            try
            {
                $U = $MMᵀ->eigenvectors();
            } catch (Exception\MatrixException|Exception\MathException $e)
            {
            }

            // n x n orthoganol matrix
            try
            {
                $V = $MᵀM->eigenvectors();
            } catch (Exception\MatrixException|Exception\MathException $e)
            {
            }

            // A rectangular diagonal matrix
            try
            {
                $S = $U->transpose()->multiply($M)->multiply($V);
            } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
            {
            }

            $diag = $S->getDiagonalElements();

            // If there is a negative singular value, we need to adjust the signs of columns in U
            if (min($diag) < 0)
            {
                try
                {
                    $sig = MatrixFactory::identity($U->getN())->getMatrix();
                } catch (Exception\OutOfBoundsException|Exception\MathException $e)
                {
                }
                foreach ($diag as $key => $value)
                {
                    {
                        $sig[$key][$key] = ($value >= 0) ? 1 : -1;
                    }
                }
                try
                {
                    $signature = MatrixFactory::createNumeric($sig);
                } catch (Exception\BadDataException|Exception\MathException $e)
                {
                }
                try
                {
                    $U = $U->multiply($signature);
                } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
                {
                }
                try
                {
                    $S = $signature->multiply($S);
                } catch (Exception\IncorrectTypeException|Exception\MathException|Exception\MatrixException $e)
                {
                }
            }

            return new SVD($U, $S, $V);
        }

        public static function SVDInvalidProperty()
        {
        }

        public static function SVDGetProperties()
        {
        }

        public static function lesserRankSVDProperties()
        {
        }

        public static function SVDProperties()
        {
        }

        public static function SVD()
        {
        }

        /**
         * Get U
         *
         * @return NumericMatrix
         */
        public function getU(): NumericMatrix
        {
            return $this->U;
        }

        /**
         * Get S
         *
         * @return NumericMatrix
         */
        public function getS(): NumericMatrix
        {
            return $this->S;
        }

        /**
         * Get V
         *
         * @return NumericMatrix
         */
        public function getV(): NumericMatrix
        {
            return $this->V;
        }

        /**
         * Get D
         *
         * @return Vector
         */
        public function getD(): Vector
        {
            return $this->D;
        }

        /**
         * Get U, S, or V matrix, or D vector
         *
         * @param string $name
         *
         * @return NumericMatrix|Vector
         * @throws \MathPHP\Exception\MatrixException
         */
        public function __get(string $name)
        {
            switch ($name)
            {
                case 'U':
                case 'S':
                case 'V':
                case 'D':
                    return $this->$name;
                default:
                    return throw new Exception\MatrixException("SVD class does not have a gettable property: $name");
            }
        }
    }
