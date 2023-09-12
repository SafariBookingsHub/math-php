<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Base;

    use Closure;
    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\IncorrectTypeException;
    use MathPHP\Exception\MathException;
    use MathPHP\Exception\MatrixException;
    use MathPHP\LinearAlgebra\MatrixFactory;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    use function array_merge;
    use function array_pop;
    use function array_reduce;
    use function array_shift;
    use function array_sum;
    use function in_array;
    use function is_float;
    use function is_int;

    class MatrixMapTest extends TestCase {
        /**
         * @return array (input, func, output)
         */
        #[ArrayShape(shape: [
            'abs'   => "array",
            'round' => "array",
            'sqrt'  => "array",
        ])] public static function dataProviderForMapCallable(): array
        {
            return [
                'abs'   => [
                    [
                        [1, -2, 3],
                        [-4, 5, -6],
                        [-7, -8, 9],
                    ],
                    'abs',
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                ],
                'round' => [
                    [
                        [1.1, 2.2, 3.3],
                        [4.4, 5.5, 6.6],
                        [7.7, 8.8, 9.9],
                    ],
                    'round',
                    [
                        [1, 2, 3],
                        [4, 6, 7],
                        [8, 9, 10],
                    ],
                ],
                'sqrt'  => [
                    [
                        [1, 4, 9],
                        [16, 25, 36],
                        [49, 64, 81],
                    ],
                    'sqrt',
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                ],
            ];
        }

        /**
         * @return array (input, func, output)
         */
        #[ArrayShape(shape: [
            'doubler' => "array",
            'add one' => "array",
        ])] public static function dataProviderForMapClosure(): array
        {
            return [
                'doubler' => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn($x) => $x * 2,
                    [
                        [2, 4, 6],
                        [8, 10, 12],
                        [14, 16, 18],
                    ],
                ],
                'add one' => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn($x) => $x + 1,
                    [
                        [2, 3, 4],
                        [5, 6, 7],
                        [8, 9, 10],
                    ],
                ],
            ];
        }

        /**
         * @return array (input, func, output)
         */
        public static function dataProviderForApplyRowsCallable(): array
        {
            return [
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'array_reverse',
                    [
                        [3, 2, 1],
                        [6, 5, 4],
                        [9, 8, 7],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'array_sum',
                    [
                        6,
                        15,
                        24,
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'array_product',
                    [
                        6,
                        120,
                        504,
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 0, 6],
                        [0, 0, 9],
                    ],
                    'array_filter',
                    [
                        [1, 2, 3],
                        [0 => 4, 2 => 6],
                        [2 => 9],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'array_flip',
                    [
                        [1 => 0, 2 => 1, 3 => 2],
                        [4 => 0, 5 => 1, 6 => 2],
                        [7 => 0, 8 => 1, 9 => 2],
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'array_keys',
                    [
                        [0, 1, 2],
                        [0, 1, 2],
                        [0, 1, 2],
                    ],
                ],
                [
                    [
                        [1, 1, 3],
                        [4, 6, 6],
                        [7, 7, 7],
                    ],
                    'array_unique',
                    [
                        [1, 2 => 3],
                        [4, 1 => 6],
                        [0 => 7],
                    ],
                ],
                [
                    [
                        [3, 2, 1],
                        [6, 5, 4],
                        [9, 8, 7],

                    ],
                    'count',
                    [
                        3,
                        3,
                        3,
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'min',
                    [
                        1,
                        4,
                        7,
                    ],
                ],
                [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    'max',
                    [
                        3,
                        6,
                        9,
                    ],
                ],
                [
                    [
                        [1, 2, 3, 1, 2, 3],
                        [4, 5, 6, 6, 6, 6],
                        [7, 8, 8, 9, 9, 9],
                    ],
                    'array_count_values',
                    [
                        [1 => 2, 2 => 2, 3 => 2],
                        [4 => 1, 5 => 1, 6 => 4],
                        [7 => 1, 8 => 2, 9 => 3],
                    ],
                ],
            ];
        }

        /**
         * @return array (input, func, output)
         * @throws \Exception
         */
        #[ArrayShape(shape: [
            'add one'                       => "array",
            'sort'                          => "array",
            'remove first and last'         => "array",
            'something strange with reduce' => "array",
            'merge'                         => "array",
            'chunk'                         => "array",
            'vectors'                       => "array",
        ])] public static function dataProviderForApplyRowsClosure(): array
        {
            return [
                'add one'                       => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn(array $row) => array_sum(array: $row) + 1,
                    [
                        7,
                        16,
                        25,
                    ],
                ],
                'sort'                          => [
                    [
                        [3, 1, 2],
                        [4, 6, 5],
                        [7, 8, 9],
                    ],
                    function (array $row) {
                        sort(array: $row);

                        return $row;
                    },
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                ],
                'remove first and last'         => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    function (array $row) {
                        array_shift(array: $row);
                        array_pop(array: $row);

                        return $row;
                    },
                    [
                        [2],
                        [5],
                        [8],
                    ],
                ],
                'something strange with reduce' => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn(array $row) => array_reduce(
                        array: $row,
                        callback: fn($carry, $item) => ($carry * $carry)
                            + $item,
                        initial: 1
                    ),
                    [
                        39,
                        906,
                        5193,
                    ],
                ],
                'merge'                         => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn(array $row) => array_merge($row, [9, 9, 9]),
                    [
                        [1, 2, 3, 9, 9, 9],
                        [4, 5, 6, 9, 9, 9],
                        [7, 8, 9, 9, 9, 9],
                    ],
                ],
                'chunk'                         => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn(array $row) => array_chunk(array: $row, length: 1),
                    [
                        [[1], [2], [3]],
                        [[4], [5], [6]],
                        [[7], [8], [9]],
                    ],
                ],
                'vectors'                       => [
                    [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    fn(array $row) => new Vector(A: $row),
                    [
                        new Vector(A: [1, 2, 3]),
                        new Vector(A: [4, 5, 6]),
                        new Vector(A: [7, 8, 9]),
                    ],
                ],
            ];
        }

        /**
         * @test         map with a callable
         * @dataProvider dataProviderForMapCallable
         *
         * @param array    $A
         * @param callable $func
         * @param array    $expected
         *
         * @throws       \Exception
         */
        public function testMapWithCallable(
            array $A,
            callable $func,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expected = MatrixFactory::create(A: $expected);

            // When
            $R = $A->map($func);

            // Then
            $this->assertEquals($expected, $R);
        }

        /**
         * @test         map with a closure
         * @dataProvider dataProviderForMapClosure
         *
         * @param array    $A
         * @param \Closure $func
         * @param array    $expected
         *
         * @throws       \Exception
         */
        public function testMapWithClosure(
            array $A,
            Closure $func,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);
            $expected = MatrixFactory::create(A: $expected);

            // When
            $R = $A->map($func);

            // Then
            $this->assertEquals($expected, $R);
        }

        /**
         * @test         applyRows with a callable
         * @dataProvider dataProviderForApplyRowsCallable
         *
         * @param array    $A
         * @param callable $func
         * @param array    $expected
         *
         * @throws       \Exception
         */
        public function testApplyRowsWithCallable(
            array $A,
            callable $func,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $R = $A->mapRows($func);

            // Then
            $this->assertEquals($expected, $R);
        }

        /**
         * @test         applyRows with a closure
         * @dataProvider dataProviderForApplyRowsClosure
         *
         * @param array    $A
         * @param \Closure $func
         * @param array    $expected
         *
         * @throws       \Exception
         */
        public function testApplyRowsWithClosure(
            array $A,
            Closure $func,
            array $expected
        ) {
            // Given
            $A = MatrixFactory::create(A: $A);

            // When
            $R = $A->mapRows($func);

            // Then
            $this->assertEquals($expected, $R);
        }

        /**
         * @test   applyRows with shuffle closure
         * @throws \Exception
         */
        public function testApplyRowsClosureShuffle()
        {
            // Given
            $A = MatrixFactory::create(A: [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8, 9],
            ]);
            $func = function (array $row) {
                shuffle(array: $row);

                return $row;
            };

            // When
            $R = $A->mapRows($func);

            // Then
            $this->assertTrue(in_array(needle: 1, haystack: $R[0]));
            $this->assertTrue(in_array(needle: 2, haystack: $R[0]));
            $this->assertTrue(in_array(needle: 3, haystack: $R[0]));
            $this->assertTrue(in_array(needle: 4, haystack: $R[1]));
            $this->assertTrue(in_array(needle: 5, haystack: $R[1]));
            $this->assertTrue(in_array(needle: 6, haystack: $R[1]));
            $this->assertTrue(in_array(needle: 7, haystack: $R[2]));
            $this->assertTrue(in_array(needle: 8, haystack: $R[2]));
            $this->assertTrue(in_array(needle: 9, haystack: $R[2]));
        }

        /**
         * @test walk
         */
        public function testWalk()
        {
            // Given
            try
            {
                $A = MatrixFactory::create(A: [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ]);
            } catch (BadDataException|MathException|MatrixException|IncorrectTypeException $e)
            {
            }

            // Then
            $func = function ($item) {
                $this->assertTrue(is_int(value: $item));
                $this->assertFalse(is_float(value: $item));
            };

            // When
            $A->walk($func);
        }
    }
