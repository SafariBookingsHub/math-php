<?php

    namespace MathPHP\Functions\Map;

    use MathPHP\Exception;

    use function count;

    /**
     * Map against elements of two or more arrays, item by item (by item ...).
     */
    class Multi {
        /**
         * Map add against multiple arrays
         *
         * [x₁ + y₁, x₂ + y₂, ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<int|float>
         *
         * @throws Exception\BadDataException
         */
        public static function add(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_fill = [];
            for ($k = 0; $k < 0 + $length_of_arrays; $k++)
            {
                $array_fill[$k] = 0;
            }
            $sums = $array_fill;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 0; $j < $number_of_arrays; $j++)
                {
                    $sums[$i] += $arrays[$j][$i];
                }
            }

            return $sums;
        }

        /**
         * Check that two or more arrays are all the same length
         *
         * @param array[] $arrays
         *
         * @return bool
         *
         * @throws Exception\BadDataException if there are not at least two arrays
         * @throws Exception\BadDataException if arrays are not equal lengths
         */
        private static function checkArrayLengths(array $arrays): bool
        {
            if (count($arrays) < 2)
            {
                throw new Exception\BadDataException('Need at least two arrays to map over');
            }

            $n = count($arrays[0]);
            foreach ($arrays as $array)
            {
                if (count($array) !== $n)
                {
                    throw new Exception\BadDataException('Lengths of arrays are not equal');
                }
            }

            return TRUE;
        }

        /**
         * Map subtract against multiple arrays
         *
         * [x₁ - y₁, x₂ - y₂, ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<int|float>
         *
         * @throws Exception\BadDataException
         */
        public static function subtract(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_map = [];
            foreach ($arrays[0] as $key => $x)
            {
                $array_map[$key] = $x;
            }
            $differences = $array_map;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 1; $j < $number_of_arrays; $j++)
                {
                    $differences[$i] -= $arrays[$j][$i];
                }
            }

            return $differences;
        }

        /**
         * Map multiply against multiple arrays
         *
         * [x₁ * y₁, x₂ * y₂, ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<int|float>
         *
         * @throws Exception\BadDataException
         */
        public static function multiply(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_fill = [];
            for ($k = 0; $k < 0 + $length_of_arrays; $k++)
            {
                $array_fill[$k] = 1;
            }
            $products = $array_fill;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 0; $j < $number_of_arrays; $j++)
                {
                    $products[$i] *= $arrays[$j][$i];
                }
            }

            return $products;
        }

        /**
         * Map divide against multiple arrays
         *
         * [x₁ / y₁, x₂ / y₂, ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<float>
         *
         * @throws Exception\BadDataException
         */
        public static function divide(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_map = [];
            foreach ($arrays[0] as $key => $x)
            {
                $array_map[$key] = $x;
            }
            $quotients = $array_map;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 1; $j < $number_of_arrays; $j++)
                {
                    $quotients[$i] /= $arrays[$j][$i];
                }
            }

            return $quotients;
        }

        /**
         * Map max against multiple arrays
         *
         * [max(x₁, y₁), max(x₂, y₂), ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<int|float>
         *
         * @throws Exception\BadDataException
         */
        public static function max(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_map = [];
            foreach ($arrays[0] as $key => $x)
            {
                $array_map[$key] = $x;
            }
            $maxes = $array_map;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 1; $j < $number_of_arrays; $j++)
                {
                    $maxes[$i] = \max($maxes[$i], $arrays[$j][$i]);
                }
            }

            return $maxes;
        }

        /* *************** *
         * PRIVATE METHODS
         * *************** */

        /**
         * Map min against multiple arrays
         *
         * [max(x₁, y₁), max(x₂, y₂), ... ]
         *
         * @param array<int|float> ...$arrays Two or more arrays of numbers
         *
         * @return array<int|float>
         *
         * @throws Exception\BadDataException
         */
        public static function min(array ...$arrays): array
        {
            self::checkArrayLengths($arrays);

            $number_of_arrays = count($arrays);
            $length_of_arrays = count($arrays[0]);
            $array_map = [];
            foreach ($arrays[0] as $key => $x)
            {
                $array_map[$key] = $x;
            }
            $mins = $array_map;

            for ($i = 0; $i < $length_of_arrays; $i++)
            {
                for ($j = 1; $j < $number_of_arrays; $j++)
                {
                    $mins[$i] = \min($mins[$i], $arrays[$j][$i]);
                }
            }

            return $mins;
        }
    }
