<?php

    namespace MathPHP\Expression;

    use MathPHP\Exception;

    use function count;
    use function is_callable;
    use function is_numeric;
    use function usort;

    use const INF;

    /**
     * A convenience class for piecewise functions.
     *
     * https://en.wikipedia.org/wiki/Piecewise
     */
    class Piecewise {
        /** @var array<array{0: float, 1: float, 2?: bool, 3?: bool}> */
        private array $intervals;

        /** @var array<callable> */
        private array $functions;

        /**
         * Validate that our inputs satisfy the conditions of a piecewise function,
         * and then assign our inputs as the object parameters.
         *
         * In our $intervals array, each interval should contain two numbers [a, b].
         * We can optionally add two booleans to an interval, signifying the openness
         * of a and b, respectfully (true means open, false means closed). If boolean
         * arguments are not supplied, the interval will be assumed to be closed.
         *
         * Examples:
         *     1. [0, 2, true, false] means an interval from 0 to 2, where 0 is open
         *        (exclusive) and 2 is closed (inclusive).
         *     2. [-10, 10] means an closed (inclusive) interval from -10 to 10
         *
         * A number of conditions need to be met for a piecewise function:
         *     o We must provide the same number of intervals as callback functions
         *     o Each function in our $functions array needs to be callable
         *     o Each interval must contain precisely 2 numbers, optionally 2 booleans
         *     o An interval defined as a point (e.g. [2, 2]) must be closed at both ends
         *     o The numbers in an interval must be increasing. Given [a, b] then b >= a.
         *     o Two intervals cannot overlap. This means that if two intervals share
         *       a start and end-point, the point must be closed on both sides. Also,
         *       we cannot start or end an interval in the middle of another interval.
         *
         * @param array<array{0: float, 1: float, 2?: bool, 3?: bool}> $intervals
         *                                                                        Array of intervals
         *                                                                        Example: [[-10, 0, false, true], [0, 2], [3, 10]]
         * @param array<callable>                                      $functions Array of callback functions
         *
         * @throws Exception\BadDataException if the number of intervals and functions are not the same
         * @throws Exception\BadDataException if any function in $functions is not callable
         * @throws Exception\BadDataException if any interval in $intervals does not contain 2 numbers
         * @throws Exception\BadDataException if any interval [a, b] is decreasing, or b < a
         * @throws Exception\BadDataException if an interval is a point that is not closed
         * @throws Exception\BadDataException if two intervals share a point that is closed at both ends
         * @throws Exception\BadDataException if one interval starts or ends inside another interval
         */
        public function __construct(array $intervals, array $functions)
        {
            Piecewise::constructorPreconditions($intervals, $functions);

            $unsortedIntervals = $intervals;

            // Sort intervals such that start of intervals is increasing
            usort($intervals, fn($a, $b) => $a[0] <=> $b[0]);

            foreach ($intervals as $interval)
            {
                // Store values from previous interval
                $lastA = $a ?? -INF;
                $lastB = $b ?? -INF;
                $lastBOpen = $bOpen ?? FALSE;

                $array_filter1 = [];
                foreach ($interval as $key => $value)
                {
                    if (is_numeric($value))
                    {
                        $array_filter1[$key] = $value;
                    }
                }
                $array_filter = $array_filter1;
                if (count($array_filter) !== 2)
                {
                    throw new Exception\BadDataException('Each interval must contain two numbers.');
                }

                // Fetch values from current interval
                $a = $interval[0];
                $b = $interval[1];
                $aOpen = $interval[2] ?? FALSE;
                $bOpen = $interval[3] ?? FALSE;
                Piecewise::checkAsAndBs($a, $b, $lastA, $lastB, $lastBOpen,
                    $aOpen,
                    $bOpen);
            }

            $this->intervals = $unsortedIntervals;
            $this->functions = $functions;
        }

        /**
         * Constructor preconditions - helper method
         *  - Same number of intervals as functions
         *  - All functions are callable
         *
         * @param array<array{0: float, 1: float, 2?: bool, 3?: bool}> $intervals
         * @param array<callable>                                      $functions
         *
         * @return void
         *
         * @throws Exception\BadDataException if the number of intervals and functions are not the same
         * @throws Exception\BadDataException if any function in $functions is not callable
         */
        private static function constructorPreconditions(
            array $intervals,
            array $functions
        ): void {
            if (count($intervals) !== count($functions))
            {
                throw new Exception\BadDataException('For a piecewise function you must provide the same number of intervals as functions.');
            }

            // @phpstan-ignore-next-line (Parameter #2 $callback of function array_filter expects callable(callable(): mixed): mixed, '\\is_callable' given.)
            $array_filter1 = [];
            foreach ($functions as $key => $value)
            {
                if (is_callable($value))
                {
                    $array_filter1[$key] = $value;
                }
            }
            $array_filter = $array_filter1;
            if (count($array_filter)
                !== count($intervals)
            )
            {
                throw new Exception\BadDataException('Not every function provided is valid. Ensure that each function is callable.');
            }
        }

        /**
         * Check the as and bs in the intervals
         * Helper method of constructor.
         *
         * @param float|int $a
         * @param float|int $b
         * @param float|int $lastA
         * @param float|int $lastB
         * @param bool      $lastBOpen
         * @param bool      $aOpen
         * @param bool      $bOpen
         *
         * @return void
         *
         * @throws Exception\BadDataException if any interval [a, b] is decreasing, or b < a
         * @throws Exception\BadDataException if an interval is a point that is not closed
         * @throws Exception\BadDataException if two intervals share a point that is closed at both ends
         * @throws Exception\BadDataException if one interval starts or ends inside another interval
         */
        private static function checkAsAndBs(
            float|int $a,
            float|int $b,
            float|int $lastA,
            float|int $lastB,
            bool $lastBOpen,
            bool $aOpen,
            bool $bOpen
        ): void {
            if (($a === $b) && ($aOpen || $bOpen))
            {
                throw new Exception\BadDataException("Your interval [{$a}, {$b}] is a point and thus needs to be closed at both ends");
            }
            if ($a > $b)
            {
                throw new Exception\BadDataException("Interval must be increasing. Try again using [{$b}, {$a}] instead of [{$a}, {$b}]");
            }
            if (($a === $lastB) && ! $aOpen && ! $lastBOpen)
            {
                throw new Exception\BadDataException("The intervals [{$lastA}, {$lastB}] and [{$a}, {$b}] share a point, but both intervals are also closed at that point. For intervals to share a point, one or both sides of that point must be open.");
            }
            if ($a < $lastB)
            {
                throw new Exception\BadDataException("The intervals [{$lastA}, {$lastB}] and [{$a}, {$b}] overlap. The subintervals of a piecewise functions cannot overlap.");
            }
        }

        public static function checkAsAndBsExceptionOverlappingIntervals()
        {
        }

        public static function checkAsAndBsExceptionTwoIntervalsSharePointNotClosedAtBothEnds(
        )
        {
        }

        public static function checkAsAndBsExceptionIntervalNotIncreasing()
        {
        }

        public static function checkAsAndBsExceptionPointNotClosed()
        {
        }

        public static function constructorPreconditionCallableException()
        {
        }

        public static function constructorPreconditionCountException()
        {
        }

        public static function duplicatedIntervalException()
        {
        }

        public static function evaluatedAtOpenPointException()
        {
        }

        public static function evaluationNotInDomainException()
        {
        }

        public static function numberOfIntervalsAndFunctionsUnequalException()
        {
        }

        public static function inputFunctionsAreNotCallableException()
        {
        }

        public static function subintervalContainsOpenPoint()
        {
        }

        public static function subintervalContainsOnePoints()
        {
        }

        public static function subintervalContainsMoreThanTwoPoints()
        {
        }

        public static function subintervalDecreasingException()
        {
        }

        public static function subintervalsOverlapException()
        {
        }

        public static function subintervalsShareClosedPointException()
        {
        }

        public static function eval()
        {
        }

        /**
         * When a callback function is being evaluated at a specific point, find the
         * the corresponding function for that point in the domain, and then return
         * the function evaluated at that point. If no function is found, throw an Exception.
         *
         * @param float $x₀ The value at which we are evaluating our piecewise function
         *
         * @return float The specific function evaluated at $x₀
         *
         * @throws Exception\BadDataException if an interval cannot be found which contains our $x₀
         */
        public function __invoke(float $x₀): float
        {
            $function = $this->getFunction($x₀);

            return $function($x₀);
        }

        /**
         * Find which subinterval our input value is contained within, and then return
         * the function that corresponds to that subinterval. If no subinterval is found
         * such that our input is contained within it, a false is returned.
         *
         * @param float $x  The value at which we are searching for a subinterval that
         *                  contains it, and thus has a corresponding function.
         *
         * @return callable Returns the function that contains $x in its domain
         *
         * @throws Exception\BadDataException if an interval cannot be found which contains our $x
         */
        private function getFunction(float $x): callable
        {
            foreach ($this->intervals as $i => $interval)
            {
                $a = $interval[0];
                $b = $interval[1];
                $aOpen = $interval[2] ?? FALSE;
                $bOpen = $interval[3] ?? FALSE;

                // Four permutations: open-open, open-closed, closed-open, closed-closed
                if ((Piecewise::openOpen($aOpen, $bOpen) && ($x > $a)
                        && ($x < $b))
                    || (Piecewise::openClosed($aOpen, $bOpen) && ($x > $a)
                        && ($x <= $b))
                    || (Piecewise::closedOpen($aOpen, $bOpen) && ($x >= $a)
                        && ($x < $b))
                    || (Piecewise::closedClosed($aOpen, $bOpen) && ($x >= $a)
                        && ($x <= $b))
                )
                {
                    return $this->functions[$i];
                }
            }

            throw new Exception\BadDataException("The input {$x} is not in the domain of this piecewise function, thus it is undefined at that point.");
        }

        /**
         * Open-open interval
         *
         * @param bool $aOpen
         * @param bool $bOpen
         *
         * @return bool
         */
        private static function openOpen(bool $aOpen, bool $bOpen): bool
        {
            return $aOpen && $bOpen;
        }

        /**
         * Open-closed interval
         *
         * @param bool $aOpen
         * @param bool $bOpen
         *
         * @return bool
         */
        private static function openClosed(bool $aOpen, bool $bOpen): bool
        {
            return $aOpen && ! $bOpen;
        }

        /**
         * Closed-open interval
         *
         * @param bool $aOpen
         * @param bool $bOpen
         *
         * @return bool
         */
        private static function closedOpen(bool $aOpen, bool $bOpen): bool
        {
            return ! $aOpen && $bOpen;
        }

        /**
         * Closed-closed interval
         *
         * @param bool $aOpen
         * @param bool $bOpen
         *
         * @return bool
         */
        private static function closedClosed(bool $aOpen, bool $bOpen): bool
        {
            return ! $aOpen && ! $bOpen;
        }
    }
