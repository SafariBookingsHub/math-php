<?php

    namespace MathPHP\Expression;

    use MathPHP\Algebra;
    use MathPHP\Exception;
    use MathPHP\Functions\Arithmetic;
    use MathPHP\Functions\Map;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\NumericSquareMatrix;
    use MathPHP\LinearAlgebra\Vector;
    use MathPHP\Number\Complex;
    use MathPHP\Number\ObjectArithmetic;

    use function abs;
    use function array_fill;
    use function array_merge;
    use function array_reverse;
    use function array_values;
    use function count;
    use function intval;
    use function is_numeric;
    use function preg_replace;
    use function str_replace;
    use function strlen;
    use function strval;
    use function trim;

    use const NAN;

    /**
     * A convenience class for one-dimension polynomials.
     *
     * This class is used to encompass typical methods and features that you can extend
     * to polynomials. For example, polynomial differentiation follows a specific rule,
     * and thus we can build a differentiation method that returns the exact derivative
     * for polynomials.
     *
     * Input arguments: simply pass in an array of coefficients in decreasing powers.
     * Make sure to put a 0 coefficient in place of powers that are not used.
     *
     * Current features:
     *     o Print a human readable string of a polynomial of any variable (default of x)
     *     o Evaluate a polynomial at any real number
     *     o Return the degree of a polynomial
     *     o Return the coefficients of a polynomial
     *     o Return the variable of a polynomial
     *     o Set the variable of an instantiated polynomial
     *     o Polynomial differentiation (exact)
     *     o Polynomial integration (indefinite integral)
     *     o Polynomial addition
     *     o Polynomial multiplication
     *
     * Examples:
     *     $polynomial = new Polynomial([1, -8, 12, 3]);
     *     echo $polynomial;                        // prints 'x³ - 8x² + 12x + 3'
     *     echo $polynomial(4);                     // prints -31
     *     echo $polynomial->getDegree();           // prints 3
     *     print_r($polynomial->getCoefficients()); // prints [1, -8, 12, 3]
     *     echo $polynomial->differentiate();       // prints '3x² - 16x + 12'
     *     echo $polynomial->integrate();           // prints '0.25x⁴ - 2.6666666666667x³ + 6x² + 3x'
     *     echo $polynomial->add($polynomial);      // prints '2x³ - 16x² + 24x + 6'
     *     echo $polynomial->multiply($polynomial); // prints 'x⁶ - 16x⁵ + 88x⁴ - 186x³ + 96x² + 72x + 9'
     *     echo $polynomial->getVariable();         // prints 'x'
     *     $polynomial->setVariable("r");
     *     echo $polynomial;                        // prints 'r³ - 8r² + 12r + 3'
     *
     * https://en.wikipedia.org/wiki/Polynomial
     */
    class Polynomial implements ObjectArithmetic {
        /**
         * @var array<string> Unicode characters for exponents
         */
        private const SYMBOLS
            = [
                '⁰',
                '¹',
                '²',
                '³',
                '⁴',
                '⁵',
                '⁶',
                '⁷',
                '⁸',
                '⁹',
            ];
        private int $degree;
        /** @var array<int|float> */
        private array $coefficients;

        /**
         * When a polynomial is instantiated, set the coefficients and degree of
         * that polynomial as its object parameters.
         *
         * @param array<int|float> $coefficients An array of coefficients in decreasing powers
         *                                       Example: new Polynomial([1, 2, 3]) will create
         *                                       a polynomial that looks like x² + 2x + 3.
         * @param string           $variable
         */
        public function __construct(
            array $coefficients,
            private string $variable = "x"
        ) {
            // Remove coefficients that are leading zeros
            $initial_count = count($coefficients);
            for ($i = 0; $i < $initial_count; $i++)
            {
                if ($coefficients[$i] != 0)
                {
                    break;
                }
                unset($coefficients[$i]);
            }

            // If coefficients remain, re-index them. Otherwise return [0] for p(x) = 0
            $coefficients = ($coefficients != []) ? array_values($coefficients)
                : [0];

            $this->degree = count($coefficients) - 1;
            $this->coefficients = $coefficients;
        }

        /**
         * Zero value: 0
         *
         * @return Polynomial
         */
        public static function createZeroValue(): ObjectArithmetic
        {
            return new Polynomial([0]);
        }

        public static function companionException()
        {
        }

        public static function checkNumericOrPolynomialException()
        {
        }

        public static function checkNumericOrPolynomialNumericInput()
        {
        }

        public static function exception()
        {
        }

        public static function rootsNAN()
        {
        }

        public static function scalarMultiplication()
        {
        }

        public static function scalarSubtraction()
        {
        }

        public static function scalarAddition()
        {
        }

        public static function multiplication()
        {
        }

        public static function subtraction()
        {
        }

        public static function addition()
        {
        }

        public static function fundamentalTheoremOfCalculus()
        {
        }

        public static function integration()
        {
        }

        public static function differentiation()
        {
        }

        public static function eval()
        {
        }

        public static function variable()
        {
        }

        public static function string()
        {
        }

        /**
         * When a polynomial is to be treated as a string, return it in a readable format.
         * Example: $polynomial = new Polynomial([1, -8, 12, 3]);
         *          echo $polynomial;
         *          // prints 'x³ - 8x² + 12x + 3'
         *
         * @return string A human readable representation of the polynomial
         */
        public function __toString(): string
        {
            $variable = $this->variable;

            // Start with an empty polynomial
            $polynomial = '';

            // Iterate over each coefficient to generate the string for each term and add to the polynomial
            foreach ($this->coefficients as $i => $coefficient)
            {
                if ($coefficient == 0)
                {
                    continue;
                }

                // Power of the current term
                $power = $this->degree - $i;

                // Build the exponent of our string as a unicode character
                $exponent = '';
                for ($j = 0; $j < strlen(strval($power)); $j++)
                {
                    $digit
                        = intval(strval($power)[$j]); // The j-th digit of $power
                    $exponent .= self::SYMBOLS[$digit];      // The corresponding unicode character
                }

                // Get the sign for the term
                $sign = ($coefficient > 0) ? '+' : '-';

                // Drop the sign from the coefficient, as it is handled by $sign
                $coefficient = abs($coefficient);

                // Drop coefficients that equal 1 (and -1) if they are not the 0th-degree term
                if (($coefficient == 1) and ($this->degree - $i != 0))
                {
                    $coefficient = '';
                }

                // Generate the $term string. No $variable term if power = 0.
                if ($power == 0)
                {
                    $term = "{$sign} {$coefficient}";
                } else
                {
                    $term = "{$sign} {$coefficient}{$variable}{$exponent} ";
                }

                // Add the current term to the polynomial
                $polynomial .= $term;
            }

            // Cleanup front and back; drop redundant ¹ and ⁰ terms from monomials
            $polynomial = trim(str_replace([$variable.'¹ ', $variable.'⁰ '],
                $variable.' ', $polynomial), '+ ');
            $polynomial = preg_replace('/^-\s/', '-', $polynomial);

            $polynomial = ($polynomial !== '') ? $polynomial : '0';

            return (string)$polynomial;
        }

        /**
         * When a polynomial is being evaluated at a point x₀, build a callback
         * function and return the value of the callback function at x₀
         * Example: $polynomial = new Polynomial([1, -8, 12, 3]);
         *          echo $polynomial(4);
         *          // prints -13
         *
         * @param float|int $x₀ The value at which we are evaluating our polynomial
         *
         * @return float The result of our polynomial evaluated at $x₀
         */
        public function __invoke(float|int $x₀): float
        {
            // Set object parameters as local variables so they can be used with the use function
            $degree = $this->degree;
            $coefficients = $this->coefficients;

            // Start with the zero polynomial
            $polynomial = fn() => 0;

            // Iterate over each coefficient to create a callback function for each term
            for ($i = 0; $i < $degree + 1; $i++)
            {
                // Create a callback function for the current term
                $term = fn($x) => $coefficients[$i] * $x ** ($degree - $i);
                // Add the new term to the polynomial
                $polynomial = Arithmetic::add($polynomial, $term);
            }

            return $polynomial();
        }

        /**
         * Return a new polynomial that is the sum of the current polynomial and an
         * input polynomial
         * Example: $polynomial = new Polynomial([3, -16, 12]); // 3x² - 16x + 12
         *          $integral   = $polynomial->integrate();     // x³ - 8x² + 12x
         *          $sum        = $polynomial->add($integral);  // x³ - 5x² - 4x + 12
         *
         * @param mixed $object_or_scalar The polynomial or scalar we are adding to our current polynomial
         *
         * @return Polynomial The sum of our polynomial objects, also a polynomial object
         *
         * @throws Exception\BadDataException
         * @throws Exception\IncorrectTypeException
         */
        public function add(mixed $object_or_scalar): Polynomial
        {
            $object_or_scalar
                = Polynomial::checkNumericOrPolynomial($object_or_scalar);

            $coefficientsA = $this->coefficients;
            $coefficientsB = $object_or_scalar->coefficients;

            // If degrees are unequal, make coefficient array sizes equal so we can do component-wise addition
            $degreeDifference = $this->getDegree()
                - $object_or_scalar->getDegree();
            if ($degreeDifference !== 0)
            {
                $zeroArray = array_fill(0, abs($degreeDifference), 0);
                if ($degreeDifference < 0)
                {
                    $coefficientsA = array_merge($zeroArray, $coefficientsA);
                } else
                {
                    $coefficientsB = array_merge($zeroArray, $coefficientsB);
                }
            }

            $coefficientsSum = Map\Multi::add($coefficientsA, $coefficientsB);

            return new Polynomial($coefficientsSum);
        }

        /**
         * Check that our input is either a number or a Polynomial
         * Convert any numbers to Polynomial objects
         *
         * @param mixed $input The variable to check
         *
         * @return Polynomial
         * @throws Exception\IncorrectTypeException
         */
        private static function checkNumericOrPolynomial(mixed $input
        ): Polynomial {
            if ($input instanceof Polynomial)
            {
                return $input;
            } elseif (is_numeric($input))
            {
                /** @var int|float $input */
                return new Polynomial([$input]);
            } else
            {
                throw new Exception\IncorrectTypeException('Input must be a Polynomial or a number');
            }
        }

        /**
         * Getter method for the degree of a polynomial
         *
         * @return int The degree of a polynomial object
         */
        public function getDegree(): int
        {
            return $this->degree;
        }

        /**
         * Getter method for the dependent variable of a polynomial
         *
         * @return string The dependent variable of a polynomial object
         */
        public function getVariable(): string
        {
            return $this->variable;
        }

        /**
         * Setter method for the dependent variable of a polynomial
         *
         * @param string $variable The new dependent variable of a polynomial object
         */
        public function setVariable(string $variable): void
        {
            $this->variable = $variable;
        }

        /**
         * Calculate the derivative of a polynomial and return it as a new polynomial
         * Example: $polynomial = new Polynomial([1, -8, 12, 3]); // x³ - 8x² + 12x + 3
         *          $derivative = $polynomial->differentiate();   // 3x² - 16x + 12
         *
         * @return Polynomial The derivative of our polynomial object, also a polynomial object
         */
        public function differentiate(): Polynomial
        {
            $derivativeCoefficients
                = []; // Start with empty set of coefficients

            // Iterate over each coefficient (except the last), differentiating term-by-term
            for ($i = 0; $i < $this->degree; $i++)
            {
                $derivativeCoefficients[] = $this->coefficients[$i]
                    * ($this->degree - $i);
            }

            // If the array of coefficients is empty, we are differentiating a constant. Return [0].
            $derivativeCoefficients = ($derivativeCoefficients !== [])
                ? $derivativeCoefficients : [0];

            return new Polynomial($derivativeCoefficients);
        }

        /**
         * Calculate the indefinite integral of a polynomial and return it as a new polynomial
         * Example: $polynomial = new Polynomial([3, -16, 12]); // 3x² - 16x + 12
         *          $integral = $polynomial->integrate();       // x³ - 8x² + 12x
         *
         * Note that this method assumes the constant of integration to be 0.
         *
         * @return Polynomial The integral of our polynomial object, also a polynomial object
         */
        public function integrate(): Polynomial
        {
            $integralCoefficients = []; // Start with empty set of coefficients

            // Iterate over each coefficient, integrating term-by-term
            for ($i = 0; $i < $this->degree + 1; $i++)
            {
                $integralCoefficients[] = $this->coefficients[$i]
                    / (($this->degree - $i) + 1);
            }
            $integralCoefficients[] = 0; // Make the constant of integration 0

            return new Polynomial($integralCoefficients);
        }

        /**
         * Return a new polynomial that is the difference of the current polynomial and an
         * input polynomial
         * Example: $polynomial = new Polynomial([3, -16, 12]);        // 3x² - 16x + 12
         *          $integral   = $polynomial->differentiate();         // 6x - 16
         *          $difference = $polynomial->subtract($derivative);  // 3x² - 22x + 28
         *
         * @param mixed $object_or_scalar The polynomial or scalar we are subtracting from our current polynomial
         *
         * @return Polynomial The difference of our polynomial objects, also a polynomial object
         *
         * @throws Exception\BadDataException
         * @throws Exception\IncorrectTypeException
         */
        public function subtract(mixed $object_or_scalar): Polynomial
        {
            $object_or_scalar
                = Polynomial::checkNumericOrPolynomial($object_or_scalar);
            $additiveInverse = $object_or_scalar->negate();

            return $this->add($additiveInverse);
        }

        /**
         * Return a new polynomial that is the negated version.
         *
         * @return Polynomial that is negated
         */
        public function negate(): Polynomial
        {
            return new Polynomial(
                Map\Single::multiply($this->coefficients, -1)
            );
        }

        /**
         * Return a new polynomial that is the product of the current polynomial and an
         * input polynomial
         * Example: $polynomial = new Polynomial([2, -16]);          // 2x - 16
         *          $integral   = $polynomial->integrate();          // x² - 16x
         *          $product    = $polynomial->multiply($integral);  // 2x³ - 48x² + 256x
         *
         * @param mixed $object_or_scalar The polynomial or scalar we are multiplying with our current polynomial
         *
         * @return Polynomial The product of our polynomial objects, also a polynomial object
         *
         * @throws Exception\IncorrectTypeException
         */
        public function multiply(mixed $object_or_scalar): Polynomial
        {
            $object_or_scalar
                = Polynomial::checkNumericOrPolynomial($object_or_scalar);
            // Calculate the degree of the product of the polynomials
            $productDegree = $this->degree + $object_or_scalar->degree;

            // Reverse the coefficients arrays so you can multiply component-wise
            $coefficientsA = array_reverse($this->coefficients);
            $coefficientsB = array_reverse($object_or_scalar->coefficients);

            // Start with an array of coefficients that all equal 0
            /** @var array<int> $productCoefficients */
            $productCoefficients = array_fill(0, $productDegree + 1, 0);

            // Iterate through the product of terms component-wise
            for ($i = 0; $i < $this->degree + 1; $i++)
            {
                for ($j = 0; $j < $object_or_scalar->degree + 1; $j++)
                {
                    // Calculate the degree of the current product
                    $degree = $productDegree - ($i + $j);

                    // Calculate the product of the coefficients
                    $product = $coefficientsA[$i] * $coefficientsB[$j];

                    // Add the product to the existing coefficient of the current degree
                    $productCoefficients[$degree]
                        = $productCoefficients[$degree] + $product;
                }
            }

            return new Polynomial($productCoefficients);
        }

        /**
         * Calculate the roots of a polynomial
         *
         * Closed form solutions only exist if the degree is less than 5
         *
         * @return array<numeric|Complex|null> of roots
         *
         * @throws Exception\IncorrectTypeException
         */
        public function roots(): array
        {
            $array_map1 = [];
            foreach ($this->coefficients as $key => $coefficient)
            {
                $array_map1[$key] = (float)$coefficient;
            }
            $array_map = $array_map1;
            $floatCoefficients = $array_map;

            switch ($this->degree)
            {
                case 0:
                    return [NULL];
                case 1:
                    return [Algebra::linear(...$floatCoefficients)];
                case 2:
                    return Algebra::quadratic(...$floatCoefficients);
                case 3:
                    return Algebra::cubic(...$floatCoefficients);
                case 4:
                    return Algebra::quartic(...$floatCoefficients);
                default:
                    return [NAN];
            }
        }

        /**
         * Companion matrix (Frobenius companion matrix of the monic polynomial)
         *
         * https://en.wikipedia.org/wiki/Companion_matrix
         *
         * p(t) = c₀ + c₁t + ⋯ + cᶰ₋₁tⁿ⁻¹ + tⁿ
         *
         *
         *        | 0 0 ⋯ 0   -c₀ |
         *        | 1 0 ⋯ 0   -c₁ |
         * C(p) = | 0 1 ⋯ 0   -c₂ |
         *        | ⋮ ⋮  ⋱ ⋮    ⋮   |
         *        | 0 0 ⋯ 1 -cᶰ₋₁ |
         *
         * @return NumericSquareMatrix
         * @throws \MathPHP\Exception\MathException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function companionMatrix(): NumericSquareMatrix
        {
            if ($this->degree === 0)
            {
                throw new Exception\OutOfBoundsException('Polynomial must be 1st degree or greater.');
            }

            $coefficients = $this->getCoefficients();
            try
            {
                $reversedCoefficients
                    = new Vector(array_reverse($coefficients));
            } catch (Exception\BadDataException $e)
            {
            }

            /* Make a column matrix without the largest factor, after setting it to 1
             *  |  -c₀  |
             *  |  -c₁  |
             *  |  -c₂  |
             *  |   ⋮   |
             *  | -cᶰ₋₁ |
             */
            try
            {
                try
                {
                    $columnMatrix
                        = Matrixfactory::createFromVectors([$reversedCoefficients])
                        ->scalarDivide(-1 * $coefficients[0])
                        ->rowExclude($this->getDegree());
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                } catch (Exception\MatrixException $e)
                {
                } catch (Exception\MathException $e)
                {
                }
            } catch (Exception\BadDataException|Exception\MatrixException|Exception\IncorrectTypeException|Exception\BadParameterException $e)
            {
            }

            /* Identity matrix with one fewer row and column than there are coefficients
             *  | 1 0 ⋯ 0 |
             *  | 0 1 ⋯ 0 |
             *  | ⋮ ⋮  ⋱ ⋮ |
             *  | 0 0 ⋯ 1 |
             */
            try
            {
                $identityMatrix = MatrixFactory::identity($columnMatrix->getM()
                    - 1);
            } catch (Exception\OutOfBoundsException|Exception\MathException $e)
            {
            }

            // Zero row to augment above identity matrix | 0 0 ⋯ 0 |
            try
            {
                $zero_row = MatrixFactory::zero(1, $columnMatrix->getM() - 1);
            } catch (Exception\BadDataException|Exception\MathException|Exception\OutOfBoundsException $e)
            {
            }

            /** Companion matrix is identity augmented above with the zero matrix and augmented to the right with the column matrix of coefficients
             *  | 0 0 ⋯ 0   -c₀ |
             *  | 1 0 ⋯ 0   -c₁ |
             *  | 0 1 ⋯ 0   -c₂ |
             *  | ⋮ ⋮  ⋱ ⋮    ⋮   |
             *  | 0 0 ⋯ 1 -cᶰ₋₁ |
             *
             * @var NumericSquareMatrix $companion
             */
            try
            {
                $companion = $identityMatrix
                    ->augmentAbove($zero_row)
                    ->augment($columnMatrix);
            } catch (Exception\BadDataException|Exception\MathException|Exception\MatrixException|Exception\IncorrectTypeException $e)
            {
            }

            return $companion;
        }

        /**
         * Getter method for the coefficients of a polynomial
         *
         * @return array<int|float> The coefficients array of a polynomial object
         */
        public function getCoefficients(): array
        {
            return $this->coefficients;
        }
    }
