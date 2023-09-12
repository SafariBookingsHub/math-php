<?php

    namespace MathPHP\NumericalAnalysis\RootFinding;

    use MathPHP\Exception;
    use MathPHP\Functions\Special;

    use function abs;

    /**
     * Bisection's Method (also known as the Binary-search method)
     *
     * In numerical analysis, the Bisection method is a method for finding successively
     * better approximations to the roots (or zeroes) of a continuous, real-valued
     * function f(x). It starts with two points $a and $b, such that f($a) and f($b)
     * have different signs (one is positive, one is negative). This lets us use
     * the intermediate value theorem to prove that there is a root $p such that
     * p is between $a and $b. We initially set $p to be the average of $a and $b
     * and analyze the result of f($p). Based on the sign, we construct a new $p that
     * is either the average of $a and the original $p, or the average of the
     * original $p and $b. We continue doing this until our function evaluation f($p)
     * is within the tolerance set on our input.
     *
     * https://en.wikipedia.org/wiki/Bisection_method
     */
    class BisectionMethod {
        /**
         * Use the Bisection Method to find the x which produces $function(x) = 0.
         *
         * @param callable  $function f(x) callback function
         * @param float|int $a        The start of the interval which contains a root
         * @param float|int $b        The end of the interval which contains a root
         * @param float|int $tol      Tolerance; How close to the actual solution we would like.
         *
         * @return int|float
         *
         * @throws Exception\OutOfBoundsException
         * @throws Exception\BadDataException
         */
        public static function solve(
            callable $function,
            float|int $a,
            float|int $b,
            float|int $tol
        ): float|int {
            // Validate input arguments
            self::validate($function, $a, $b, $tol);

            do
            {
                $f⟮a⟯ = $function($a);
                $p = ($a + $b) / 2;     // construct the midpoint
                $f⟮p⟯ = $function($p);
                $dif
                    = abs($f⟮p⟯);       // the magnitude of our function at the midpoint
                if (Special::sgn($f⟮p⟯) !== Special::sgn($f⟮a⟯))
                {
                    $b
                        = $p;
                } else
                {
                    $a
                        = $p;
                }
            } while ($dif > $tol);

            return $p;
        }

        /**
         * Verify the input arguments are valid for correct use of the bisection
         * method. If the tolerance is less than zero, an Exception will be thrown.
         * If f($a) and f($b) have the same sign, we cannot use the intermediate
         * value theorem to guarantee a root is between $a and $b. This exposes the
         * risk of an endless loop, so we throw an Exception. If $a = $b, then clearly
         * we cannot run our loop as $a and $b will themselves be the midpoint, so we
         * throw an Exception.
         *
         * @param Callable  $function f(x) callback function
         * @param float|int $a        The start of the interval which contains a root
         * @param float|int $b        The end of the interval which contains a root
         * @param float|int $tol      Tolerance; How close to the actual solution we would like.
         *
         * @throws Exception\OutOfBoundsException if $tol (the tolerance) is negative
         * @throws Exception\BadDataException if $a = $b
         * @throws Exception\BadDataException if f($a) and f($b) share the same sign
         */
        private static function validate(
            callable $function,
            float|int $a,
            float|int $b,
            float|int $tol
        ): void {
            Validation::tolerance($tol);
            Validation::interval($a, $b);

            $f⟮a⟯ = $function($a);
            $f⟮b⟯ = $function($b);
            if (Special::sgn($f⟮a⟯) === Special::sgn($f⟮b⟯))
            {
                throw new Exception\BadDataException(
                    'Input function has the same sign at the start and end of the interval. Choose start and end points such that the function evaluated at those points has a different sign (one positive, one negative).'
                );
            }
        }

        public static function bisectionMethodExceptionSameSigns()
        {
        }

        public static function bisectionMethodExceptionZeroInterval()
        {
        }

        public static function bisectionMethodExceptionNegativeTolerance()
        {
        }

        public static function solveEToNegativeXTimesSomeStuff()
        {
        }

        public static function solveXSquaredSubtractThree()
        {
        }

        public static function solveXCubedSubtractXSubtractTwo()
        {
        }

        public static function solvePolynomialWithFourRootsUsingPolynomial()
        {
        }

        public static function solvePolynomialWithFourRootsUsingClosure()
        {
        }
    }
