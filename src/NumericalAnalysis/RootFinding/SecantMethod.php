<?php

    namespace MathPHP\NumericalAnalysis\RootFinding;

    use MathPHP\Exception;

    use function abs;

    /**
     * Secant Method (also known as the Newton–Raphson method)
     *
     * In numerical analysis, the Secant Method is a method for finding successively
     * better approximations to the roots (or zeroes) of a real-valued function. It is
     * a variation of Newton's Method that we can utilize when the derivative of our
     * function f'(x) is not explicity given or cannot be calculated.
     *
     * https://en.wikipedia.org/wiki/Secant_method
     */
    class SecantMethod {
        /**
         * Use the Secant Method to find the x which produces $f(x) = 0 by calculating
         * the average change between our initial approximations and moving our
         * approximations closer to the root.
         *
         * @param callable  $function f(x) callback function
         * @param float|int $p₀       First initial approximation
         * @param float|int $p₁       Second initial approximation
         * @param float|int $tol      Tolerance; How close to the actual solution we would like.
         *
         * @return int|float
         *
         * @throws Exception\OutOfBoundsException if $tol (the tolerance) is negative
         * @throws Exception\BadDataException if $p₀ = $p₁
         */
        public static function solve(callable $function, float|int $p₀, float|int $p₁, float|int $tol): float|int
        {
            Validation::tolerance($tol);
            Validation::interval($p₀, $p₁);

            do
            {
                $q₀ = $function($p₀);
                $q₁ = $function($p₁);
                $slope = ($q₁ - $q₀) / ($p₁ - $p₀);
                $p = $p₁ - $q₁ / $slope;
                $dif = abs($p - $p₁);
                $p₀ = $p₁;
                $p₁ = $p;
            } while ($dif > $tol);

            return $p;
        }

        public function exceptionZeroInterval()
        {
        }

        public function exceptionNegativeTolerance()
        {
        }

        public function cosXSubtractTwoX()
        {
        }

        public function XSquaredSubtractFive()
        {
        }

        public function XCubedSubtractXPlusOne()
        {
        }

        public function solvePolynomialWithFourRootsUsingPolynomial()
        {
        }

        public function solvePolynomialWithFourRootsUsingClosure()
        {
        }
    }
