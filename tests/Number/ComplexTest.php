<?php

    namespace MathPHP\Tests\Number;

    use MathPHP\Exception;
    use MathPHP\Number\Complex;
    use MathPHP\Number\ObjectArithmetic;
    use PHPUnit\Framework\TestCase;

    use function exp;
    use function pi;
    use function sqrt;

    use const M_PI;

    class ComplexTest extends TestCase {
        public static function dataProviderForToString(): array
        {
            return [
                [0, 0, '0'],
                [1, 0, '1'],
                [-1, 0, '-1'],
                [0, 1, '1i'],
                [0, -1, '-1i'],
                [1, 1, '1 + 1i'],
                [1, 2, '1 + 2i'],
                [2, 1, '2 + 1i'],
                [2, 2, '2 + 2i'],
                [1, -1, '1 - 1i'],
                [1, -2, '1 - 2i'],
                [2, -1, '2 - 1i'],
                [2, -2, '2 - 2i'],
                [-1, 1, '-1 + 1i'],
                [-1, 2, '-1 + 2i'],
                [-2, 1, '-2 + 1i'],
                [-2, 2, '-2 + 2i'],
                [-1, -1, '-1 - 1i'],
                [-1, -2, '-1 - 2i'],
                [-2, -1, '-2 - 1i'],
                [-2, -2, '-2 - 2i'],
            ];
        }

        public static function dataProviderForComplexConjugate(): array
        {
            return [
                [0, 0],
                [1, 0],
                [0, 1],
                [1, 1],
                [1, 2],
                [3, 7],
            ];
        }

        public static function dataProviderForAbs(): array
        {
            return [
                [0, 0, 0],
                [1, 0, 1],
                [0, 1, 1],
                [1, 2, sqrt(5)],
                [2, 1, sqrt(5)],
                [2, 2, sqrt(8)],
                [-1, 0, 1],
                [0, -1, 1],
                [-1, 2, sqrt(5)],
                [2, -1, sqrt(5)],
                [-2, -2, sqrt(8)],
            ];
        }

        public static function dataProviderForArg(): array
        {
            return [
                [0, 1, M_PI / 2],
                [0, -1, M_PI / -2],
                [1, 1, 0.7853981633974483],
                [2, 2, 0.7853981633974483],
                [3, 3, 0.7853981633974483],
                [1, 2, 1.1071487177940904],
                [2, 1, 0.4636476090008061],
                [3, 1.4, 0.4366271598135413],
                [M_PI, 1, 0.30816907111598496],
                [1, M_PI, 1.2626272556789115],
                [-1, 1, 2.356194490192345],
                [1, -1, -0.78539816],
                [-1, -1, -2.35619449],
            ];
        }

        public static function dataProviderForSqrt(): array
        {
            return [
                [8, -6, 3, -1],
                [
                    9,
                    4,
                    sqrt((9 + sqrt(97)) / 2),
                    2 * sqrt(2 / (9 + sqrt(97))),
                ],
                [-4, -6, 1.2671, -2.3676],
                [0, 9, 2.1213203, 2.1213203],
                [10, -6, 3.2910412, -0.9115656],
                [-4, 0, 0, 2],
                [-3, 0, 0, 1.7320508],
                [-2, 0, 0, 1.4142136],
                [-1, 0, 0, 1],
                [0, 0, 0, 0],
            ];
        }

        public static function dataProviderForRoots(): array
        {
            return [
                [8, -6, ['r' => 3, 'i' => -1], ['r' => -3, 'i' => 1]],
                [
                    9,
                    4,
                    [
                        'r' => sqrt((9 + sqrt(97)) / 2),
                        'i' => 2 * sqrt(2 / (9 + sqrt(97))),
                    ],
                    [
                        'r' => -sqrt((9 + sqrt(97)) / 2),
                        'i' => -2 * sqrt(2 / (9 + sqrt(97))),
                    ],
                ],
                [
                    -4,
                    -6,
                    ['r' => 1.2671, 'i' => -2.3676],
                    ['r' => -1.2671, 'i' => 2.3676],
                ],
                [
                    0,
                    9,
                    ['r' => 2.1213203, 'i' => 2.1213203],
                    ['r' => -2.1213203, 'i' => -2.1213203],
                ],
                [
                    10,
                    -6,
                    ['r' => 3.2910412, 'i' => -0.9115656],
                    ['r' => -3.2910412, 'i' => 0.9115656],
                ],
                [
                    3,
                    3,
                    ['r' => 1.90298, 'i' => 0.78824],
                    ['r' => -1.90298, 'i' => -0.78824],
                ],
                [-4, 0, ['r' => 0, 'i' => 2], ['r' => 0, 'i' => -2]],
                [
                    -3,
                    0,
                    ['r' => 0, 'i' => 1.7320508],
                    ['r' => 0, 'i' => -1.7320508],
                ],
                [
                    -2,
                    0,
                    ['r' => 0, 'i' => 1.4142136],
                    ['r' => 0, 'i' => -1.4142136],
                ],
                [-1, 0, ['r' => 0, 'i' => 1], ['r' => 0, 'i' => -1]],
                [0, 0, ['r' => 0, 'i' => 0], ['r' => 0, 'i' => -0]],
            ];
        }

        public static function dataProviderForNegate(): array
        {
            return [
                [0, 0, 0, 0],
                [1, 0, -1, 0],
                [0, 1, 0, -1],
                [1, 2, -1, -2],
                [3, 4, -3, -4],
                [-4, -5, 4, 5],
                [-6, 3, 6, -3],
            ];
        }

        /**
         * Test data created with: http://www.analyzemath.com/Calculators/complex_polar_exp.html
         * Python: cmath.polar(complex(5, 2))
         *
         * @return array
         */
        public static function dataProviderForPolarForm(): array
        {
            return [
                [5, 2, 5.385164807134504, 0.3805063771123649],
                [49.90, 25.42, 56.00166426098424, 0.4711542560514266],
                [-1, -1, 1.4142135623730951, -2.356194490192345],
                [1, 0, 1, 0],
                [0, 1, 1, 1.5707963267948966],
                [0, 0, 0, 0],
                [M_PI, 2, 3.724191778237173, 0.5669115049410094],
                [8, 9, 12.041594578792296, 0.844153986113171],
                [814, -54, 815.7891884549587, -0.06624200592698481],
                [-5, -3, 5.830951894845301, -2.601173153319209],
            ];
        }

        /**
         * Test data created from wolfram alpha: example: https://www.wolframalpha.com/input/?i=e%5E%281%2B2*i%29
         *
         * @return array
         */
        public static function dataProviderForExp(): array
        {
            return [
                [0, pi(), -1, 0],
                [
                    1,
                    2,
                    -1.13120438375681363843125525551079471062886799582652575021772191,
                    2.47172667200481892761693089355166453273619036924100818420075883,
                ],
                [5, 0, exp(5), 0],
            ];
        }

        public static function dataProviderForAdd(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    ['r' => 4, 'i' => -3],
                    ['r' => 7, 'i' => -1],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    ['r' => 4, 'i' => -3],
                    ['r' => 4, 'i' => -3],
                ],
                [
                    ['r' => -3, 'i' => -2],
                    ['r' => 4, 'i' => 3],
                    ['r' => 1, 'i' => 1],
                ],
                [
                    ['r' => 7, 'i' => 6],
                    ['r' => 4, 'i' => 4],
                    ['r' => 11, 'i' => 10],
                ],
            ];
        }

        public static function dataProviderForAddReal(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    5,
                    ['r' => 8, 'i' => 2],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    5,
                    ['r' => 5, 'i' => 0],
                ],
                [
                    ['r' => 3, 'i' => 2],
                    -2,
                    ['r' => 1, 'i' => 2],
                ],
            ];
        }

        public static function dataProviderForSubtract(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    ['r' => 4, 'i' => -3],
                    ['r' => -1, 'i' => 5],
                ],
                [
                    ['r' => 3, 'i' => 2],
                    ['r' => 4, 'i' => -3],
                    ['r' => -1, 'i' => 5],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    ['r' => 4, 'i' => -3],
                    ['r' => -4, 'i' => 3],
                ],
                [
                    ['r' => -3, 'i' => -2],
                    ['r' => 4, 'i' => 3],
                    ['r' => -7, 'i' => -5],
                ],
                [
                    ['r' => 7, 'i' => 6],
                    ['r' => 4, 'i' => 4],
                    ['r' => 3, 'i' => 2],
                ],
            ];
        }

        public static function dataProviderForSubtractReal(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    5,
                    ['r' => -2, 'i' => 2],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    5,
                    ['r' => -5, 'i' => 0],
                ],
                [
                    ['r' => 3, 'i' => 2],
                    -2,
                    ['r' => 5, 'i' => 2],
                ],
            ];
        }

        public static function dataProviderForMultiply(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    ['r' => 1, 'i' => 4],
                    ['r' => -5, 'i' => 14],
                ],
                [
                    ['r' => 3, 'i' => 13],
                    ['r' => 7, 'i' => 17],
                    ['r' => -200, 'i' => 142],
                ],
                [
                    ['r' => 6, 'i' => 8],
                    ['r' => 4, 'i' => -9],
                    ['r' => 96, 'i' => -22],
                ],
                [
                    ['r' => -56, 'i' => 3],
                    ['r' => -84, 'i' => -4],
                    ['r' => 4716, 'i' => -28],
                ],
            ];
        }

        public static function dataProviderForMultiplyReal(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 1],
                    2,
                    ['r' => 6, 'i' => 2],
                ],
                [
                    ['r' => 30, 'i' => 13],
                    2,
                    ['r' => 60, 'i' => 26],
                ],
            ];
        }

        public static function dataProviderForDivide(): array
        {
            return [
                [
                    ['r' => 3, 'i' => 2],
                    ['r' => 4, 'i' => -3],
                    ['r' => 0.24, 'i' => 0.68],
                ],
                [
                    ['r' => 5, 'i' => 5],
                    ['r' => 6, 'i' => 2],
                    ['r' => 1, 'i' => 1 / 2],
                ],
                [
                    ['r' => 6, 'i' => 2],
                    ['r' => 7, 'i' => -7],
                    ['r' => 2 / 7, 'i' => 4 / 7],
                ],
                [
                    ['r' => -56, 'i' => 3],
                    ['r' => -84, 'i' => -4],
                    ['r' => 69 / 104, 'i' => -7 / 104],
                ],
            ];
        }

        public static function dataProviderForDivideReal(): array
        {
            return [
                [
                    ['r' => 4, 'i' => 1],
                    2,
                    ['r' => 2, 'i' => 1 / 2],
                ],
                [
                    ['r' => 60, 'i' => 9],
                    3,
                    ['r' => 20, 'i' => 3],
                ],
            ];
        }

        /**
         * https://www.wolframalpha.com/input/?i=%281%2B2*i%29%5E%283%2B4*i%29
         */
        public static function dataProviderForPowNumber(): array
        {
            return [
                [
                    ['r' => 1, 'i' => 2],
                    5,
                    ['r' => 41, 'i' => -38],
                ],
                [
                    ['r' => 7, 'i' => 13],
                    0,
                    ['r' => 1, 'i' => 0],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    0,
                    ['r' => 1, 'i' => 0],
                ],
            ];
        }

        /**
         * https://www.wolframalpha.com/input/?i=%281%2B2*i%29%5E%283%2B4*i%29
         * R: complex(real=1, imaginary=2)**complex(real=3, imaginary=4)
         * Python: complex(1,2)**complex(3,4)
         */
        public static function dataProviderForPow(): array
        {
            return [
                [
                    ['r' => 1, 'i' => 2],
                    ['r' => 3, 'i' => 4],
                    [
                        'r' => 0.12900959407446689407705233965244724409184546447229472777070039,
                        'i' => 0.033924092905170126697617854622547901540547320222677608399651655,
                    ],
                ],
                [
                    ['r' => 1, 'i' => 2],
                    ['r' => 5, 'i' => 0],
                    ['r' => 41, 'i' => -38],
                ],
                [
                    ['r' => 1, 'i' => 2],
                    ['r' => 0, 'i' => 0],
                    ['r' => 1, 'i' => 0],
                ],
                [
                    ['r' => 2, 'i' => 2],
                    ['r' => -2, 'i' => -2],
                    ['r' => -0.5251869237872764, 'i' => 0.2928344540350973],
                ],
                [
                    ['r' => -2, 'i' => -2],
                    ['r' => -2, 'i' => -2],
                    ['r' => -9.807565e-04, 'i' => 5.468516e-04],
                ],
                [
                    ['r' => 83, 'i' => 24],
                    ['r' => 4, 'i' => 6],
                    ['r' => -9503538.518957876, 'i' => 3956277.4270916637],
                ],
                [
                    ['r' => 2, 'i' => 3],
                    ['r' => 4, 'i' => 5],
                    ['r' => -0.7530458367485596, 'i' => -0.9864287886477445],
                ],
                [
                    ['r' => -2, 'i' => 3],
                    ['r' => 4, 'i' => 5],
                    [
                        'r' => -0.0027390773950467934,
                        'i' => 0.0021275418620241996,
                    ],
                ],
                [
                    ['r' => 2, 'i' => -3],
                    ['r' => 4, 'i' => 5],
                    ['r' => -18175.48769862527, 'i' => 14117.567839250678],
                ],
                [
                    ['r' => 2, 'i' => 3],
                    ['r' => -4, 'i' => 5],
                    [
                        'r' => -3.4315770545555685e-05,
                        'i' => 2.6654317433786663e-05,
                    ],
                ],
                [
                    ['r' => 2, 'i' => 3],
                    ['r' => 4, 'i' => -5],
                    ['r' => -18175.48769862527, 'i' => -14117.567839250678],
                ],
                [
                    ['r' => 0, 'i' => 0],
                    ['r' => 0, 'i' => 0],
                    ['r' => 1, 'i' => 0],
                ],
            ];
        }

        public static function dataProviderForInverse(): array
        {
            return [
                [1, 0, 1, 0],
                [0, 1, 0, -1],
                [1, 1, 1 / 2, -1 / 2],
                [4, 6, 1 / 13, -3 / 26],
                [-4, 6, -1 / 13, -3 / 26],
                [4, -6, 1 / 13, 3 / 26],
                [-4, -6, -1 / 13, 3 / 26],
            ];
        }

        public static function dataProviderForComplexNumbers(): array
        {
            return [
                [0, 0],
                [1, 0],
                [0, 1],
                [1, 1],
                [1, 2],
                [2, 1],
                [2, 2],
                [3, 4],
                [5, 3],
                [-1, 0],
                [0, 1],
                [-1, 1],
                [-1, 2],
                [-2, 1],
                [-2, 2],
                [-3, 4],
                [-5, 3],
                [1, 0],
                [0, -1],
                [1, -1],
                [1, -2],
                [2, -1],
                [2, -2],
                [3, -4],
                [5, -3],
                [-1, 0],
                [0, -1],
                [-1, -1],
                [-1, -2],
                [-2, -1],
                [-2, -2],
                [-3, -4],
                [-5, -3],
            ];
        }

        /**
         * @test Interfaces
         */
        public function testObjectArithmeticInterface()
        {
            // Given
            $c = new Complex(1, 2);

            // Then
            $this->assertInstanceOf(ObjectArithmetic::class, $c);
        }

        public function testZeroValue()
        {
            // Given
            $c = Complex::createZeroValue();

            // Then
            $this->assertEquals(0, $c->r);
            $this->assertEquals(0, $c->i);
        }

        /**
         * @test         __toString returns the proper string representation of a complex number
         * @dataProvider dataProviderForToString
         *
         * @param number $r
         * @param number $i
         * @param string $expected
         */
        public function testToString($r, $i, string $expected)
        {
            // Given
            $complex = new Complex($r, $i);

            // When
            $string = $complex->__toString();

            // Then
            $this->assertEquals($expected, $string);
            $this->assertEquals($expected, (string)$complex);
        }

        /**
         * @test __get returns r and i
         */
        public function testGet()
        {
            // Given
            $r = 1;
            $i = 2;
            $complex = new Complex($r, $i);

            // Then
            $this->assertEquals($r, $complex->r);
            $this->assertEquals($i, $complex->i);
        }

        /**
         * @test __get throws an Exception\BadParameterException if a property other than r or i is attempted
         */
        public function testGetException()
        {
            // Given
            $r = 1;
            $i = 2;
            $complex = new Complex($r, $i);

            // Then
            $this->expectException(Exception\BadParameterException::class);

            // When
            $z = $complex->z;
        }

        /**
         * @test         complexConjugate returns the expected Complex number
         * @dataProvider dataProviderForComplexConjugate
         *
         * @param number $r
         * @param number $i
         */
        public function testComplexConjugate($r, $i)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            $cc = $c->complexConjugate();

            // Then
            $this->assertEquals($c->r, $cc->r);
            $this->assertEquals($c->i, -1 * $cc->i);
        }

        /**
         * @test         abs returns the expected value
         * @dataProvider dataProviderForAbs
         *
         * @param number $r
         * @param number $i
         * @param number $expected
         */
        public function testAbs($r, $i, $expected)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            $abs = $c->abs();

            // Then
            $this->assertEquals($expected, $abs);
        }

        /**
         * @test         arg returns the expected value
         * @dataProvider dataProviderForArg
         *
         * @param number $r
         * @param number $i
         * @param number $expected
         */
        public function testArg($r, $i, $expected)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            $arg = $c->arg();

            // Then
            $this->assertEqualsWithDelta($expected, $arg, 0.00000001);
        }

        /**
         * @test         sqrt returns the expected positive Complex root
         * @dataProvider dataProviderForSqrt
         *
         * @param number $r
         * @param number $i
         * @param number $expected_r
         * @param number $expected_i
         */
        public function testSqrt($r, $i, $expected_r, $expected_i)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            $sqrt = $c->sqrt();

            // Then
            $this->assertEqualsWithDelta($expected_r, $sqrt->r, 0.00001);
            $this->assertEqualsWithDelta($expected_i, $sqrt->i, 0.00001);
        }

        /**
         * @test         roots returns the expected array of two Complex roots
         * @dataProvider dataProviderForRoots
         *
         * @param number $r
         * @param number $i
         * @param array  $z₁
         * @param array  $z₂
         */
        public function testRoots($r, $i, array $z₁, array $z₂)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            $roots = $c->roots();

            // Then
            $this->assertEqualsWithDelta($z₁['r'], $roots[0]->r, 0.00001);
            $this->assertEqualsWithDelta($z₁['i'], $roots[0]->i, 0.00001);
            $this->assertEqualsWithDelta($z₂['r'], $roots[1]->r, 0.00001);
            $this->assertEqualsWithDelta($z₂['i'], $roots[1]->i, 0.00001);
        }

        /**
         * @test         negate returns the expected complex number with signs negated
         * @dataProvider dataProviderForNegate
         *
         * @param number $r₁
         * @param number $i₁
         * @param number $r₂
         * @param number $i₂
         */
        public function testNegate($r₁, $i₁, $r₂, $i₂)
        {
            // Given
            $c = new Complex($r₁, $i₁);
            $expected = new Complex($r₂, $i₂);

            // When
            $negated = $c->negate();

            // Then
            $this->assertTrue($negated->equals($expected));
            $this->assertEquals($expected->r, $negated->r);
            $this->assertEquals($expected->i, $negated->i);
        }

        /**
         * @test         polarForm returns the expected complex number
         * @dataProvider dataProviderForPolarForm
         *
         * @param number $r
         * @param number $i
         * @param number $expectedR
         * @param number $expectedθ
         */
        public function testPolarForm($r, $i, $expectedR, $expectedθ)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            [$polarR, $polarθ] = $c->polarForm();

            // Then
            $this->assertEqualsWithDelta($expectedR, $polarR, 0.00001);
            $this->assertEqualsWithDelta($expectedθ, $polarθ, 0.00001);
        }

        /**
         * @test         exp returns the expected complex number
         * @dataProvider dataProviderForExp
         *
         * @param number $r₁
         * @param number $i₁
         * @param number $r₂
         * @param number $i₂
         */
        public function testExp($r₁, $i₁, $r₂, $i₂)
        {
            // Given
            $c = new Complex($r₁, $i₁);
            $expected = new Complex($r₂, $i₂);

            // When
            $exp = $c->exp();

            // Then
            $this->assertEqualsWithDelta($expected->r, $exp->r, 0.00001);
            $this->assertEqualsWithDelta($expected->i, $exp->i, 0.00001);
        }

        /**
         * @test         add of two complex numbers returns the expected complex number
         * @dataProvider dataProviderForAdd
         *
         * @param array $complex1
         * @param array $complex2
         * @param array $expected
         */
        public function testAdd(
            array $complex1,
            array $complex2,
            array $expected
        ) {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);
            $c2 = new Complex($complex2['r'], $complex2['i']);

            // When
            try
            {
                $result = $c1->add($c2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         add of real numbers returns the expected complex number
         * @dataProvider dataProviderForAddReal
         */
        public function testAddReal($complex, $real, $expected)
        {
            // Given
            $c = new Complex($complex['r'], $complex['i']);

            // When
            try
            {
                $result = $c->add($real);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         subtract of two complex numbers returns the expected complex number
         * @dataProvider dataProviderForSubtract
         *
         * @param array $complex1
         * @param array $complex2
         * @param array $expected
         */
        public function testSubtract(
            array $complex1,
            array $complex2,
            array $expected
        ) {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);
            $c2 = new Complex($complex2['r'], $complex2['i']);

            // When
            try
            {
                $result = $c1->subtract($c2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         subtract of real numbers returns the expected complex number
         * @dataProvider dataProviderForSubtractReal
         */
        public function testSubtractReal($complex, $real, $expected)
        {
            // Given
            $c = new Complex($complex['r'], $complex['i']);

            // When
            try
            {
                $result = $c->subtract($real);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         multiply of two complex numbers returns the expected complex number
         * @dataProvider dataProviderForMultiply
         *
         * @param array $complex1
         * @param array $complex2
         * @param array $expected
         */
        public function testMultiply(
            array $complex1,
            array $complex2,
            array $expected
        ) {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);
            $c2 = new Complex($complex2['r'], $complex2['i']);

            // When
            try
            {
                $result = $c1->multiply($c2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         multiply of real numbers returns the expected complex number
         * @dataProvider dataProviderForMultiplyReal
         */
        public function testMultiplyReal($complex, $real, $expected)
        {
            // Given
            $c = new Complex($complex['r'], $complex['i']);

            // When
            try
            {
                $result = $c->multiply($real);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test         divide of two complex numbers returns the expected complex number
         * @dataProvider dataProviderForDivide
         *
         * @param array $complex1
         * @param array $complex2
         * @param array $expected
         *
         * @throws \MathPHP\Exception\BadDataException
         */
        public function testDivide(
            array $complex1,
            array $complex2,
            array $expected
        ) {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);
            $c2 = new Complex($complex2['r'], $complex2['i']);

            // When
            try
            {
                $result = $c1->divide($c2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected['r'], $result->r, 0.00001);
            $this->assertEqualsWithDelta($expected['i'], $result->i, 0.00001);
        }

        /**
         * @test         divide of real numbers returns the expected complex number
         * @dataProvider dataProviderForDivideReal
         */
        public function testDivideReal($complex, $real, $expected)
        {
            // Given
            $c = new Complex($complex['r'], $complex['i']);

            // When
            try
            {
                try
                {
                    $result = $c->divide($real);
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                }
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEquals($expected['r'], $result->r);
            $this->assertEquals($expected['i'], $result->i);
        }

        /**
         * @test add throws an Exception\IncorrectTypeException when the argument is not a number or complex number
         */
        public function testComplexAddException()
        {
            // Given
            $complex = new Complex(1, 1);

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                $complex->add("string");
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test subtract throws an Exception\IncorrectTypeException when the argument is not a number or complex number
         */
        public function testComplexSubtractException()
        {
            // Given
            $complex = new Complex(1, 1);

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                $complex->subtract("string");
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test multiply throws an Exception\IncorrectTypeException when the argument is not a number or complex number
         */
        public function testComplexMultiplyException()
        {
            // Given
            $complex = new Complex(1, 1);

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                $complex->multiply("string");
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test divide throws an Exception\IncorrectTypeException when the argument is not a number or complex number
         */
        public function testComplexDivideException()
        {
            // Given
            $complex = new Complex(1, 1);

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                try
                {
                    $complex->divide("string");
                } catch (Exception\BadDataException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                }
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         pow of complex numbers raised to a number returns the expected complex number
         * @dataProvider dataProviderForPowNumber
         *
         * @param array  $complex1
         * @param number $number
         * @param array  $expected
         */
        public function testPowNumber(array $complex1, $number, array $expected)
        {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);

            // When
            try
            {
                $result = $c1->pow($number);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected['r'], $result->r, 0.00001);
            $this->assertEqualsWithDelta($expected['i'], $result->i, 0.00001);
        }

        /**
         * @test         pow of two complex numbers returns the expected complex number
         * @dataProvider dataProviderForPow
         *
         * @param array $complex1
         * @param array $complex2
         * @param array $expected
         */
        public function testPow(
            array $complex1,
            array $complex2,
            array $expected
        ) {
            // Given
            $c1 = new Complex($complex1['r'], $complex1['i']);
            $c2 = new Complex($complex2['r'], $complex2['i']);

            // When
            try
            {
                $result = $c1->pow($c2);
            } catch (Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected['r'], $result->r,
                0.00000001);
            $this->assertEqualsWithDelta($expected['i'], $result->i,
                0.00000001);
        }

        /**
         * @test pow IncorrectTypeException
         */
        public function testComplexPowTypeError()
        {
            // Given
            $c = new Complex(1, 2);

            // And
            $nonNumber = 'KaPoW!';

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                $c->pow($nonNumber);
            } catch (Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test         inverse returns the expected complex number
         * @dataProvider dataProviderForInverse
         *
         * @param number $r
         * @param number $i
         * @param number $expected_r
         * @param number $expected_i
         */
        public function testInverse($r, $i, $expected_r, $expected_i)
        {
            // Given
            $c = new Complex($r, $i);

            // When
            try
            {
                $inverse = $c->inverse();
            } catch (Exception\BadDataException $e)
            {
            }

            // Then
            $this->assertEqualsWithDelta($expected_r, $inverse->r, 0.00001);
            $this->assertEqualsWithDelta($expected_i, $inverse->i, 0.00001);
        }

        /**
         * @test inverse throws an Exception\BadDataException when value is 0 + 0i
         */
        public function testInverseException()
        {
            // Given
            $complex = new Complex(0, 0);

            // Then
            $this->expectException(Exception\BadDataException::class);

            // When
            try
            {
                $complex->inverse();
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         equals returns true if the complex numbers are the same
         * @dataProvider dataProviderForComplexNumbers
         *
         * @param number $r
         * @param number $i
         */
        public function testEqualsTrue($r, $i)
        {
            // Given
            $c1 = new Complex($r, $i);
            $c2 = new Complex($r, $i);

            // When
            $isEqual = $c1->Equals($c2);

            // Then
            $this->assertTrue($isEqual);
        }

        /**
         * @test         equals returns false if the complex numbers are different
         * @dataProvider dataProviderForComplexNumbers
         *
         * @param number $r
         * @param number $i
         */
        public function testEqualsFalse($r, $i)
        {
            // Given
            $c1 = new Complex($r, $i);
            $c2 = new Complex($r + 1, $i - 1);

            // When
            $isNotEqual = $c1->Equals($c2);

            // Then
            $this->assertFalse($isNotEqual);
        }
    }
