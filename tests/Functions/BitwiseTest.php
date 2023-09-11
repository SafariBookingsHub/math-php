<?php

    namespace MathPHP\Tests\Functions;

    use MathPHP\Functions\Bitwise;
    use PHPUnit\Framework\TestCase;

    use const PHP_INT_MAX;
    use const PHP_INT_MIN;

    class BitwiseTest extends TestCase {
        public static function dataProviderForBitwiseAdd(): array
        {
            return [
                [
                    1,
                    1,
                    [
                        'overflow' => FALSE,
                        'value'    => 2,
                    ],
                ],
                [
                    1,
                    -1,
                    [
                        'overflow' => TRUE,
                        'value'    => 0,
                    ],
                ],
                [
                    PHP_INT_MAX,
                    1,
                    [
                        'overflow' => FALSE,
                        'value'    => PHP_INT_MIN,
                    ],
                ],
                [
                    -1,
                    -1,
                    [
                        'overflow' => TRUE,
                        'value'    => -2,
                    ],
                ],
                [
                    PHP_INT_MIN,
                    PHP_INT_MIN,
                    [
                        'overflow' => TRUE,
                        'value'    => 0,
                    ],
                ],
                [
                    PHP_INT_MIN,
                    PHP_INT_MAX,
                    [
                        'overflow' => FALSE,
                        'value'    => -1,
                    ],
                ],
                [
                    0,
                    0,
                    [
                        'overflow' => FALSE,
                        'value'    => 0,
                    ],
                ],

            ];
        }

        /**
         * @test         add
         * @dataProvider dataProviderForBitwiseAdd
         *
         * @param int   $a
         * @param int   $b
         * @param array $expected
         */
        public function testBitwiseAdd(int $a, int $b, array $expected)
        {
            // When
            $sum = Bitwise::add($a, $b);

            // Then
            $this->assertEquals($expected, $sum);
        }
    }
