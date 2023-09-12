<?php

    namespace MathPHP\NumericalAnalysis\RootFinding;

    use MathPHP\Exception;

    /**
     * Common validation methods for root finding techniques
     */
    class Validation {
        /**
         * Throw an exception if the tolerance is negative.
         *
         * @param float|int $tol Tolerance; How close to the actual solution we would like.
         *
         * @throws Exception\OutOfBoundsException if $tol (the tolerance) is negative
         */
        public static function tolerance(float|int $tol): void
        {
            if ($tol < 0)
            {
                throw new Exception\OutOfBoundsException('Tolerance must be greater than zero.');
            }
        }

        /**
         * Verify that the start and end of of an interval are distinct numbers.
         *
         * @param float|int $a The start of the interval
         * @param float|int $b The end of the interval
         *
         * @throws Exception\BadDataException if $a = $b
         */
        public static function interval(float|int $a, float|int $b): void
        {
            if ($a === $b)
            {
                throw new Exception\BadDataException('Start point and end point of interval cannot be the same.');
            }
        }

        public static function isSubintervalsMultipleNotMultiple()
        {
        }

        public static function isSpacingConstantWhenNotConstant()
        {
        }

        public static function intervalSamePoints()
        {
        }

        public static function intervalNotTheSame()
        {
        }

        public static function toleranceNegative()
        {
        }

        public static function toleranceNotNegative()
        {
        }
    }
