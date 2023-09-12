<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Complex;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\LinearAlgebra\ComplexMatrix;
    use MathPHP\Number\Complex;
    use MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;
    use PHPUnit\Framework\TestCase;

    /**
     * Complex Matrix Axioms
     *  - Conjugate Transpose
     *    - (A + B)ᴴ = Aᴴ + Bᴴ
     *    - (zA)ᴴ = z‾Aᴴ
     *    - (AB)ᴴ = BᴴAᴴ
     *    - (Aᴴ)ᴴ = A
     *    - det(Aᴴ) = ‾det(A)‾
     */
    class ComplexMatrixAxiomsTest extends TestCase {
        use MatrixDataProvider;

        /**
         * @test (A + B)ᴴ = Aᴴ + Bᴴ
         * A and B must be the same dimensions, where ᴴ is the conjugate transpose
         */
        public function testConjugateTransposeAddition()
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: [
                    [new Complex(r: 1, i: 0), new Complex(r: -2, i: -1)],
                    [new Complex(r: 1, i: 1), new Complex(r: 0, i: 1)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new ComplexMatrix(A: [
                    [new Complex(r: 2, i: 2), new Complex(r: 2, i: -1)],
                    [new Complex(r: 1, i: 4), new Complex(r: 3, i: -2)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $Aᴴ ＋ Bᴴ = $A->conjugateTranspose()
                    ->add(B: $B->conjugateTranspose());
            } catch (IncorrectTypeException $e)
            {
            }
            try
            {
                $⟮A ＋ B⟯ᴴ = $A->add(object_or_scalar: $B)->conjugateTranspose();
            } catch (IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($⟮A ＋ B⟯ᴴ->getMatrix(), $Aᴴ ＋ Bᴴ->getMatrix());
        }

        /**
         * @test (zA)ᴴ = z‾Aᴴ
         * z is a complex number and z‾ is the conjugate
         */
        public function testConjugateTransposeScalarMultiplication()
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: [
                    [new Complex(r: 1, i: 0), new Complex(r: -2, i: -1)],
                    [new Complex(r: 1, i: 1), new Complex(r: 0, i: 1)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }
            $z = new Complex(r: 3, i: -2);

            // When
            $⟮zA⟯ᴴ = $A->scalarMultiply(λ: $z)->conjugateTranspose();
            $z‾Aᴴ = $A->conjugateTranspose()
                ->scalarMultiply(λ: $z->complexConjugate());

            // Then
            $this->assertEquals($⟮zA⟯ᴴ->getMatrix(), $z‾Aᴴ->getMatrix());
        }

        /**
         * @test (AB)ᴴ = BᴴAᴴ
         */
        public function testConjugateTransposeMultiplication()
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: [
                    [new Complex(r: 1, i: 0), new Complex(r: -2, i: -1)],
                    [new Complex(r: 1, i: 1), new Complex(r: 0, i: 1)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }
            try
            {
                $B = new ComplexMatrix(A: [
                    [new Complex(r: 2, i: 2), new Complex(r: 2, i: -1)],
                    [new Complex(r: 1, i: 4), new Complex(r: 3, i: -2)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $⟮AB⟯ᴴ = $A->multiply(object_or_scalar: $B)
                        ->conjugateTranspose();
                } catch (IncorrectTypeException $e)
                {
                } catch (MatrixException $e)
                {
                } catch (MathException $e)
                {
                }
            } catch (IncorrectTypeException|MatrixException $e)
            {
            }
            try
            {
                try
                {
                    $BᴴAᴴ = $B->conjugateTranspose()
                        ->multiply(object_or_scalar: $A->conjugateTranspose());
                } catch (IncorrectTypeException $e)
                {
                } catch (MatrixException $e)
                {
                } catch (MathException $e)
                {
                }
            } catch (IncorrectTypeException|MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($⟮AB⟯ᴴ->getMatrix(), $BᴴAᴴ->getMatrix());
        }

        /**
         * @test (Aᴴ)ᴴ = A
         * Hermitian transposition is an involution
         */
        public function testConjugateTransposeHermitianTranspositionIsanInvolution(
        )
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: [
                    [new Complex(r: 1, i: 0), new Complex(r: -2, i: -1)],
                    [new Complex(r: 1, i: 1), new Complex(r: 0, i: 1)],
                ]);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }

            // When
            $⟮Aᴴ⟯ᴴ = $A->conjugateTranspose()->conjugateTranspose();

            // Then
            $this->assertEquals($⟮Aᴴ⟯ᴴ->getMatrix(), $A->getMatrix());
        }

        /**
         * @test         det(Aᴴ) = ‾det(A)‾
         * If A is a square matrix, then determinant of conjugate transpose is the same as the complex conjugate of the determinant
         *
         * @dataProvider dataProviderForComplexSquareObjectMatrix
         * @array        $A
         */
        public function testConjugateTransposeDeterminant(array $A)
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: $A);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                try
                {
                    $det⟮Aᴴ⟯ = $A->conjugateTranspose()->det();
                } catch (BadParameterException $e)
                {
                } catch (IncorrectTypeException $e)
                {
                } catch (MatrixException $e)
                {
                }
            } catch (MatrixException $e)
            {
            }
            try
            {
                try
                {
                    $‾det⟮A⟯‾ = $A->det()->complexConjugate();
                } catch (BadParameterException $e)
                {
                } catch (IncorrectTypeException $e)
                {
                } catch (MatrixException $e)
                {
                }
            } catch (MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($‾det⟮A⟯‾, $det⟮Aᴴ⟯);
        }

        /**
         * @test         tr(Aᴴ) = ‾tr(A)‾
         * If A is a square matrix, then trace of conjugate transpose is the same as the complex conjugate of the trace
         *
         * @dataProvider dataProviderForComplexSquareObjectMatrix
         * @array        $A
         */
        public function testConjugateTransposeTrace(array $A)
        {
            // Given
            try
            {
                $A = new ComplexMatrix(A: $A);
            } catch (BadDataException|MathException|IncorrectTypeException $e)
            {
            }

            // When
            try
            {
                $tr⟮Aᴴ⟯ = $A->conjugateTranspose()->trace();
            } catch (MatrixException $e)
            {
            }
            try
            {
                $‾tr⟮A⟯‾ = $A->trace()->complexConjugate();
            } catch (MatrixException $e)
            {
            }

            // Then
            $this->assertEquals($‾tr⟮A⟯‾, $tr⟮Aᴴ⟯);
        }
    }
