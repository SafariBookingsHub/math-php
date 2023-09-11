<?php

    namespace MathPHP\Functions\Map;

    use MathPHP\Exception;

    /**
     * Map functions against a single array
     */
    class Single {
        /**
         * Map addition
         *
         * @param array<int|float> $xs
         * @param int|float        $k Number to add to each element
         *
         * @return array<int|float>
         */
        public static function add(array $xs, $k): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x + $k;
            }

            return $array_map;
        }

        /**
         * Map subtract
         *
         * @param array<int|float> $xs
         * @param int|float        $k Number to subtract from each element
         *
         * @return array<int|float>
         */
        public static function subtract(array $xs, $k): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x - $k;
            }

            return $array_map;
        }

        /**
         * Map multiply
         *
         * @param array<int|float> $xs
         * @param int|float        $k Number to multiply to each element
         *
         * @return array<int|float>
         */
        public static function multiply(array $xs, $k): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x * $k;
            }

            return $array_map;
        }

        /**
         * Map Divide
         *
         * @param array<int|float> $xs
         * @param int|float        $k Number to divide each element by
         *
         * @return array<float>
         */
        public static function divide(array $xs, $k): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x / $k;
            }

            return $array_map;
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
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x ** 2;
            }

            return $array_map;
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
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x ** 3;
            }

            return $array_map;
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
            $array_filter = [];
            foreach ($xs as $key => $x)
            {
                if ($x == 0)
                {
                    $array_filter[$key] = $x;
                }
            }
            $zeros = $array_filter;
            if ( ! empty($zeros))
            {
                throw new Exception\BadDataException('Cannot compute the reciprocal of 0');
            }

            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = 1 / $x;
            }

            return $array_map;
        }

        /**
         * Map raise to a power
         *
         * @param array<int|float> $xs
         * @param int|float        $n
         *
         * @return array<int|float>
         */
        public static function pow(array $xs, $n): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = $x ** $n;
            }

            return $array_map;
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
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = \sqrt($x);
            }

            return $array_map;
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
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = \abs($x);
            }

            return $array_map;
        }

        /**
         * Map min value
         * Each element in array is compared against the value,
         * and the min of each is returned.
         *
         * @param array<int|float> $xs
         * @param int|float        $value
         *
         * @return array<int|float>
         */
        public static function min(array $xs, $value): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = \min($x, $value);
            }

            return $array_map;
        }

        /**
         * Map max value
         * Each element in the array is compared against the value,
         * and the max of each is returned.
         *
         * @param array<int|float> $xs
         * @param int|float        $value
         *
         * @return array<int|float>
         */
        public static function max(array $xs, $value): array
        {
            $array_map = [];
            foreach ($xs as $key => $x)
            {
                $array_map[$key] = \max($x, $value);
            }

            return $array_map;
        }
    }
