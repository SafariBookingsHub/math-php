<?php

    namespace MathPHP\LinearAlgebra;

    use MathPHP\Exception;
    use MathPHP\Number\Complex;
    use MathPHP\Number\ObjectArithmetic;

    use function get_class;

    class ComplexMatrix extends ObjectMatrix {
        /** @var Complex[][] Matrix array of arrays */
        protected array $A;

        public function __construct(array $A)
        {
            $this->validateComplexData($A);

            parent::__construct($A);
        }

        /**
         * Validate the matrix is entirely complex
         *
         * @param array<array<object>> $A
         *
         * @throws Exception\IncorrectTypeException if all elements are not complex
         */
        protected function validateComplexData(array $A): void
        {
            foreach ($A as $i => $row)
                foreach ($row as $object)
                    if ( ! ($object instanceof Complex))
                        throw new Exception\IncorrectTypeException("All elements in the complex matrix must be complex. Got "
                            .$object::class);
        }

        /**
         * Zero value: [[0 + 0i]]
         *
         * @return ComplexMatrix
         */
        public static function createZeroValue(): ObjectArithmetic
        {
            try
            {
                return new ComplexMatrix([[new Complex(0, 0)]]);
            } catch (Exception\BadDataException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MathException $e)
            {
            }
        }

        /**
         * Conjugate Transpose - Aᴴ, also denoted as A*
         *
         * Take the transpose and then take the complex conjugate of each complex-number entry.
         *
         * https://en.wikipedia.org/wiki/Conjugate_transpose
         *
         * @return ComplexMatrix
         */
        public function conjugateTranspose(): Matrix
        {
            try
            {
                return $this->transpose()->map(
                    fn(Complex $c) => $c->complexConjugate()
                );
            } catch (Exception\IncorrectTypeException $e)
            {
            } catch (Exception\MatrixException $e)
            {
            }
        }

        public function constructorException()
        {
        }

        public function construction()
        {
        }
    }
