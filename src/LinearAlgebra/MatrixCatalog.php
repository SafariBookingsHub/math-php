<?php

    namespace MathPHP\LinearAlgebra;

    use MathPHP\Number\ObjectArithmetic;

    /**
     * @template T
     */
    class MatrixCatalog {
        /** @var Matrix<T> transpose */
        private Matrix $Aᵀ;

        /** @var Matrix<T> inverse */
        private Matrix $A⁻¹;

        private Reduction\RowEchelonForm $REF;

        private Reduction\ReducedRowEchelonForm $RREF;

        private Decomposition\LU $LU;

        private Decomposition\QR $QR;

        private Decomposition\Cholesky $cholesky;

        private Decomposition\Crout $crout;

        private Decomposition\SVD $SVD;

        /** @var int|float|ObjectArithmetic determinant */
        private int|float|ObjectArithmetic $det;

        /**************************************************************************
         * DERIVED MATRICES
         *  - transpose
         *  - inverse
         **************************************************************************/

        // TRANSPOSE

        /**
         * @param Matrix<T> $Aᵀ
         */
        public function addTranspose(Matrix $Aᵀ): void
        {
            $this->Aᵀ = $Aᵀ;
        }

        public function hasTranspose(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->Aᵀ);
        }

        /**
         * @return Matrix<T>
         */
        public function getTranspose(): Matrix
        {
            return $this->Aᵀ;
        }

        // INVERSE

        /**
         * @param Matrix<T> $A⁻¹
         */
        public function addInverse(Matrix $A⁻¹): void
        {
            $this->A⁻¹ = $A⁻¹;
        }

        public function hasInverse(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->A⁻¹);
        }

        /**
         * @return Matrix<T>
         */
        public function getInverse(): Matrix
        {
            return $this->A⁻¹;
        }

        /**************************************************************************
         * MATRIX REDUCTIONS
         *  - ref (row echelon form)
         *  - rref (reduced row echelon form)
         **************************************************************************/

        // ROW ECHELON FORM

        public function addRowEchelonForm(Reduction\RowEchelonForm $REF): void
        {
            $this->REF = $REF;
        }

        public function hasRowEchelonForm(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->REF);
        }

        public function getRowEchelonForm(): Reduction\RowEchelonForm
        {
            return $this->REF;
        }

        // REDUCED ROW ECHELON FORM

        public function addReducedRowEchelonForm(
            Reduction\ReducedRowEchelonForm $RREF
        ): void {
            $this->RREF = $RREF;
        }

        public function hasReducedRowEchelonForm(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->RREF);
        }

        public function getReducedRowEchelonForm(
        ): Reduction\ReducedRowEchelonForm
        {
            return $this->RREF;
        }

        /**************************************************************************
         * MATRIX DECOMPOSITIONS
         *  - LU decomposition
         *  - QR decomposition
         *  - Cholesky decomposition
         *  - Crout decomposition
         *  - SVD
         **************************************************************************/

        // LU DECOMPOSITION

        public function addLuDecomposition(Decomposition\LU $LU): void
        {
            $this->LU = $LU;
        }

        public function hasLuDecomposition(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->LU);
        }

        public function getLuDecomposition(): Decomposition\LU
        {
            return $this->LU;
        }

        // QR DECOMPOSITION

        public function addQrDecomposition(Decomposition\QR $QR): void
        {
            $this->QR = $QR;
        }

        public function hasQrDecomposition(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->QR);
        }

        public function getQrDecomposition(): Decomposition\QR
        {
            return $this->QR;
        }

        // CHOLESKY DECOMPOSITION

        public function addCholeskyDecomposition(
            Decomposition\Cholesky $cholesky
        ): void {
            $this->cholesky = $cholesky;
        }

        public function hasCholeskyDecomposition(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->cholesky);
        }

        public function getCholeskyDecomposition(): Decomposition\Cholesky
        {
            return $this->cholesky;
        }

        // CROUT DECOMPOSITION

        public function addCroutDecomposition(Decomposition\Crout $crout): void
        {
            $this->crout = $crout;
        }

        public function hasCroutDecomposition(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->crout);
        }

        public function getCroutDecomposition(): Decomposition\Crout
        {
            return $this->crout;
        }

        // SVD

        public function addSVD(Decomposition\SVD $SVD): void
        {
            $this->SVD = $SVD;
        }

        public function hasSVD(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->SVD);
        }

        public function getSVD(): Decomposition\SVD
        {
            return $this->SVD;
        }
        /**************************************************************************
         * DERIVED DATA
         *  - determinant
         **************************************************************************/

        // DETERMINANT

        public function addDeterminant(ObjectArithmetic|float|int $det): void
        {
            $this->det = $det;
        }

        public function hasDeterminant(): bool
        {
            // @phpstan-ignore-next-line
            return isset($this->det);
        }

        public function getDeterminant(): ObjectArithmetic|float|int
        {
            return $this->det;
        }
    }
