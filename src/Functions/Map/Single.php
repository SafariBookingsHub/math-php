<?php

    namespace MathPHP\Functions\Map;

    use MathPHP\Exception;

    use function abs;
    use function max;
    use function min;
    use function sqrt;

    /**
     * Map functions against a single array
     */
    class Single {
        /**
         * Map addition
         *
         * @param array<int|float> $xs
         * @param float|int        $k Number to add to each element
         *
         * @return array<int|float>
         */
        public static function add(array $xs, float|int $k): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x + $k;
            }

            return $array_map1;
        }

        /**
         * Map subtract
         *
         * @param array<int|float> $xs
         * @param float|int        $k Number to subtract from each element
         *
         * @return array<int|float>
         */
        public static function subtract(array $xs, float|int $k): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x - $k;
            }

            return $array_map1;
        }

        /**
         * Map multiply
         *
         * @param array<int|float> $xs
         * @param float|int        $k Number to multiply to each element
         *
         * @return array<int|float>
         */
        public static function multiply(array $xs, float|int $k): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x * $k;
            }

            return $array_map1;
        }

        /**
         * Map Divide
         *
         * @param array<int|float> $xs
         * @param float|int        $k Number to divide each element by
         *
         * @return array<float>
         */
        public static function divide(array $xs, float|int $k): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x / $k;
            }

            return $array_map1;
        }

        /**
         * Map square
         *
         * @param array<int|float> $xs
         *
         * @return array<int|float>
         */
        public static function square(array $xs): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x ** 2;
            }

            return $array_map1;
        }

        /**
         * Map cube
         *
         * @param array<int|float> $xs
         *
         * @return array<int|float>
         */
        public static function cube(array $xs): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x ** 3;
            }

            return $array_map1;
        }

        /**
         * Map reciprocal
         * x := 1/x
         *
         * @param array<int|float> $xs
         *
         * @return array<float>
         *
         * @throws Exception\BadDataException if 0 is one of the numbers
         */
        public static function reciprocal(array $xs): array
        {
            $array_filter1 = [];
            foreach ($xs as $key => $x)
            {
                if ($x == 0)
                {
                    $array_filter1[$key] = $x;
                }
            }
            $array_filter = $array_filter1;
            $zeros = $array_filter;
            if ( ! empty($zeros))
            {
                {
                    throw new Exception\BadDataException('Cannot compute the reciprocal of 0');
                }
            }

            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = 1 / $x;
            }

            return $array_map1;
        }

        /**
         * Map raise to a power
         *
         * @param array<int|float> $xs
         * @param float|int        $n
         *
         * @return array<int|float>
         */
        public static function pow(array $xs, float|int $n): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = $x ** $n;
            }

            return $array_map1;
        }

        /**
         * Map square root
         *
         * @param array<int|float> $xs
         *
         * @return array<float>
         */
        public static function sqrt(array $xs): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = sqrt($x);
            }

            return $array_map1;
        }

        /**
         * Map absolute value
         *
         * @param array<int|float> $xs
         *
         * @return array<int|float>
         */
        public static function abs(array $xs): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = abs($x);
            }

            return $array_map1;
        }

        /**
         * Map min value
         * Each element in array is compared against the value,
         * and the min of each is returned.
         *
         * @param array<int|float> $xs
         * @param float|int        $value
         *
         * @return array<int|float>
         */
        public static function min(array $xs, float|int $value): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = min($x, $value);
            }

            return $array_map1;
        }

        /**
         * Map max value
         * Each element in the array is compared against the value,
         * and the max of each is returned.
         *
         * @param array<int|float> $xs
         * @param float|int        $value
         *
         * @return array<int|float>
         */
        public static function max(array $xs, float|int $value): array
        {
            $array_map1 = [];
            foreach ($xs as $key => $x)
            {
                $array_map1[$key] = max($x, $value);
            }

            return $array_map1;
        }

        public static function reciprocalWithZeros()
        {
        }
    }
