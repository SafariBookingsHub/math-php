<?php

    namespace MathPHP\Tests\Probability\Distribution\Multivariate;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Exception;
    use MathPHP\Probability\Distribution\Multivariate\Hypergeometric;
    use PHPUnit\Framework\TestCase;

    class HypergeometricTest extends TestCase {
        /**
         * Test data created with R (extraDistr) dmvhyper(picks, quantities, numOfPicks)
         * Example: dmvhyper(c(2,2,2), c(5,10,15), 6)
         *
         * @return array
         */
        public static function dataProviderForTestHypergeometric(): array
        {
            return [
                [
                    [15, 10, 15],
                    [2, 2, 2],
                    496125 / 3838380,
                ],
                [
                    [5, 10, 15],
                    [2, 2, 2],
                    47250 / 593775,
                ],
                [
                    [5, 10, 15],
                    [2, 4, 0],
                    0.003536693,
                ],
                [
                    [5, 10, 15],
                    [2, 0, 4],
                    0.02298851,
                ],
                [
                    [5, 10, 15],
                    [4, 0, 2],
                    0.0008841733,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    1,
                ],
                [
                    [1, 1, 1],
                    [1, 1, 0],
                    1 / 3,
                ],
                [
                    [1, 1, 1],
                    [1, 0, 1],
                    1 / 3,
                ],
                [
                    [1, 1, 1],
                    [0, 1, 1],
                    1 / 3,
                ],
                [
                    [14, 11, 50],
                    [4, 5, 31],
                    0.004778598,
                ],
            ];
        }

        /**
         * @return array
         */
        #[ArrayShape([
            'float'  => "array[]",
            'string' => "array[]",
            'empty'  => "array[]"
        ])] public static function dataProviderForConstructorExceptions(): array
        {
            return [
                'float'  => [
                    [1.5, 1, 6],
                ],
                'string' => [
                    [10, 'k', 6],
                ],
                'empty'  => [
                    [],
                ],
            ];
        }

        /**
         * @return array
         */
        #[ArrayShape([
            'float'      => "array[]",
            'string'     => "array[]",
            'mismatched' => "array[]"
        ])] public static function dataProviderForPmfExceptions(): array
        {
            return [
                'float'      => [
                    [.5, 1, 6],
                ],
                'string'     => [
                    [10, 'k', 6],
                ],
                'mismatched' => [
                    [-1, 6],
                ],
            ];
        }

        /**
         * @return array
         */
        #[ArrayShape([
            'K too small' => "array[]",
            'k too small' => "array[]",
            'k too big'   => "array[]"
        ])] public static function dataProviderForBoundsExceptions(): array
        {
            return [
                'K too small' => [
                    [0, 10, 6],
                    [0, 2, 2],
                ],
                'k too small' => [
                    [5, 10, 15],
                    [-1, 2, 2],
                ],
                'k too big'   => [
                    [5, 10, 15],
                    [6, 2, 2],
                ],
            ];
        }

        /**
         * @test         pmf
         * @dataProvider dataProviderForTestHypergeometric
         */
        public function testHypergeometric(
            array $quantities,
            array $picks,
            $expected
        ) {
            try
            {
                $dist = new Hypergeometric($quantities);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $this->assertEqualsWithDelta($expected, $dist->pmf($picks),
                    0.00000001);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         __construct
         * @dataProvider dataProviderForConstructorExceptions
         */
        public function testConstructorException($quantities)
        {
            $this->expectException(Exception\BadDataException::class);
            try
            {
                $dist = new Hypergeometric($quantities);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         pmf
         * @dataProvider dataProviderForPmfExceptions
         */
        public function testPmfException($ks)
        {
            $this->expectException(Exception\BadDataException::class);
            try
            {
                $dist = new Hypergeometric([10, 10, 10]);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $prob = $dist->pmf($ks);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test         pmf, __construct
         * @dataProvider dataProviderForBoundsExceptions
         */
        public function testBoundsExceptions($Ks, $ks)
        {
            $this->expectException(Exception\OutOfBoundsException::class);
            try
            {
                $dist = new Hypergeometric($Ks);
            } catch (Exception\BadDataException $e)
            {
            }
            try
            {
                $prob = $dist->pmf($ks);
            } catch (Exception\BadDataException $e)
            {
            }
        }
    }
