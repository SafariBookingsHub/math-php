<?php

    namespace MathPHP\Functions;

    use MathPHP\Exception;

    use function abs;
    use function array_diff_key;
    use function explode;
    use function print_r;
    use function substr;

    class Support {
        private const ε = 0.00000000001;

        /**
         * Checks that the values of the parameters passed
         * to a function fall within the defined bounds.
         * The parameter limits are defined using ISO 31-11 notation.
         * https://en.wikipedia.org/wiki/ISO_31-11
         *
         *  (a,b) = a <  x <  b
         *  [a,b) = a <= x <  b
         *  (a,b] = a <  x <= b
         *  [a,b] = a <= x <= b
         *
         * @param array<string, string>    $limits Boundary limit definitions for each parameter
         *                                         ['var1' => limit, 'var2' => limit, ...]
         * @param array<string, int|float> $params Parameters and their value to check against the defined limits
         *                                         ['var1' => value, 'var2' => value, ...]
         *
         * @return bool True if all parameters are within defined limits
         *
         * @throws Exception\BadParameterException if a parameter without bounds limits is defined
         * @throws Exception\OutOfBoundsException if any parameter is outside the defined limits
         * @throws Exception\BadDataException if an unknown bounds character is used
         */
        public static function checkLimits(array $limits, array $params): bool
        {
            // All parameters should have limit bounds defined
            $undefined_limits = array_diff_key($params, $limits);
            if ( ! empty($undefined_limits))
            {
                {
                    throw new Exception\BadParameterException('Parameter without bounds limit defined: '
                        .print_r($undefined_limits, TRUE));
                }
            }

            foreach ($params as $variable => $value)
            {
                // Remove the first character: ( or [
                $lower_endpoint = substr($limits[$variable], 0, 1);

                // Remove the last character: ) or ]
                $upper_endpoint = substr($limits[$variable], -1, 1);

                // Set the lower and upper limits: #,#
                [$lower_limit, $upper_limit] = explode(',',
                    substr($limits[$variable], 1, -1));

                // If the lower limit is -∞, we are always in bounds.
                if ($lower_limit != "-∞")
                    switch ($lower_endpoint)
                    {
                        case '(':
                            if ($value <= $lower_limit)
                            {
                                throw new Exception\OutOfBoundsException("{$variable} must be > {$lower_limit} (lower bound), given {$value}");
                            }
                            break;
                        case '[':
                            if ($value < $lower_limit)
                            {
                                throw new Exception\OutOfBoundsException("{$variable} must be >= {$lower_limit} (lower bound), given {$value}");
                            }
                            break;
                        default:
                            throw new Exception\BadDataException("Unknown lower endpoint character: {$lower_limit}");
                    }

                // If the upper limit is ∞, we are always in bounds.
                if ($upper_limit != "∞")
                    switch ($upper_endpoint)
                    {
                        case ')':
                            if ($value >= $upper_limit)
                            {
                                throw new Exception\OutOfBoundsException("{$variable} must be < {$upper_limit} (upper bound), given {$value}");
                            }
                            break;
                        case ']':
                            if ($value > $upper_limit)
                            {
                                throw new Exception\OutOfBoundsException("{$variable} must be <= {$upper_limit} (upper bound), given {$value}");
                            }
                            break;
                        default:
                            throw new Exception\BadDataException("Unknown upper endpoint character: {$upper_endpoint}");
                    }
            }

            return TRUE;
        }

        /**
         * Is the number equivalent to zero?
         * Due to floating-point arithmetic, zero might be represented as an infinitesimal quantity.
         *
         * @param float $x
         * @param float $ε
         *
         * @return boolean true if equivalent to zero; false otherwise
         */
        public static function isZero(float $x, float $ε = self::ε): bool
        {
            return ($x == 0) || (abs($x) <= $ε);
        }

        /**
         * Is the number equivalent to a non-zero value?
         * Due to floating-point arithmetic, zero might be represented as an infinitesimal quantity.
         *
         * @param float $x
         * @param float $ε
         *
         * @return boolean true if equivalent to a non-zero value; false otherwise
         */
        public static function isNotZero(float $x, float $ε = self::ε): bool
        {
            return ($x != 0) && (abs($x) > $ε);
        }

        /**
         * Are two numbers equivalent up to a tiny tolerance?
         *
         * @param float $x
         * @param float $y
         * @param float $ε
         *
         * @return bool
         */
        public static function isEqual(
            float $x,
            float $y,
            float $ε = self::ε
        ): bool {
            return ($x == $y) || (abs($x - $y) < $ε);
        }

        /**
         * Are two numbers not equal given a tiny tolerance?
         *
         * @param float $x
         * @param float $y
         * @param float $ε
         *
         * @return bool
         */
        public static function isNotEqual(
            float $x,
            float $y,
            float $ε = self::ε
        ): bool {
            return ($x != $y) && (abs($x - $y) >= $ε);
        }

        public static function isNotEqualOutsideOfTolerance()
        {
        }

        public static function isNotEqualWithinTolerance()
        {
        }

        public static function isEqualOutsideOfTolerance()
        {
        }

        public static function isEqualWithinTolerance()
        {
        }

        public static function isNotEqualWhenEqual()
        {
        }

        public static function isEqualWhenNotEqual()
        {
        }

        public static function isNotZeroOutsideOfTolerance()
        {
        }

        public static function isNotZeroWithinTolerance()
        {
        }

        public static function isZeroOutsideOfTolerance()
        {
        }

        public static function isZeroWithinTolerance()
        {
        }

        public static function isNotZeroFalse()
        {
        }

        public static function isNotZeroTrue()
        {
        }

        public static function isZeroFalse()
        {
        }

        public static function isZeroTrue()
        {
        }

        public static function checkLimitsUndefinedParameterException()
        {
        }

        public static function checkLimitsUpperLimitEndpointException()
        {
        }

        public static function checkLimitsLowerLimitEndpointException()
        {
        }

        public static function checkLimitsUpperLimitException()
        {
        }

        public static function checkLimitsUpperLimit()
        {
        }

        public static function checkLimitsLowerLimitException()
        {
        }

        public static function checkLimitsLowerLimit()
        {
        }
    }
