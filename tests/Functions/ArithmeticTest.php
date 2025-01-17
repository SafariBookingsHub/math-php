<?php

    namespace MathPHP\Tests\Functions;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Functions\Arithmetic;
    use PHPUnit\Framework\TestCase;

    class ArithmeticTest extends TestCase {
        #[ArrayShape([
            'Σ(0) = 123'         => "int[]",
            'Σ(5) = 1148'        => "int[]",
            'Σ(-5) = -902'       => "int[]",
            'Σ(100) = 108748123' => "int[]",
            'Σ(-100) = 90752123' => "int[]",
        ])] public static function dataProviderForSum(): array
        {
            return [
                'Σ(0) = 123'         => [0, 123],
                'Σ(5) = 1148'        => [5, 1148],
                'Σ(-5) = -902'       => [-5, -902],
                'Σ(100) = 108748123' => [100, 108748123],
                'Σ(-100) = 90752123' => [-100, 90752123],
            ];
        }

        #[ArrayShape([
            'Π(0) = 108'         => "int[]",
            'Π(5) = -212'        => "int[]",
            'Π(-5) = 378'        => "int[]",
            'Π(100) = 981708'    => "int[]",
            'Π(-100) = -1001492' => "int[]",
        ])] public static function dataProviderForMultiply(): array
        {
            return [
                'Π(0) = 108'         => [0, 108],
                'Π(5) = -212'        => [5, -212],
                'Π(-5) = 378'        => [-5, 378],
                'Π(100) = 981708'    => [100, 981708],
                'Π(-100) = -1001492' => [-100, -1001492],
            ];
        }

        #[ArrayShape([
            'Σ(0) = 480'    => "int[]",
            'Σ(5) = 1555'   => "int[]",
            'Σ(-5) = -3845' => "int[]",
        ])] public static function dataProviderForMultipleSums(): array
        {
            return [
                'Σ(0) = 480'    => [0, 480],
                'Σ(5) = 1555'   => [5, 1555],
                'Σ(-5) = -3845' => [-5, -3845],
            ];
        }

        #[ArrayShape([
            'Π(0) = 0'     => "int[]",
            'Π(5) = -140'  => "int[]",
            'Π(-5) = -210' => "int[]",
        ])] public static function dataProviderForMultipleProducts(): array
        {
            return [
                'Π(0) = 0'     => [0, 0],
                'Π(5) = -140'  => [5, -140],
                'Π(-5) = -210' => [-5, -210],
            ];
        }

        #[ArrayShape([
            'Π(0) = -18'  => "int[]",
            'Π(5) = -48'  => "int[]",
            'Π(-5) = 112' => "int[]",
        ])] public static function dataProviderForNestedArithmetic(): array
        {
            return [
                'Π(0) = -18'  => [0, -18],
                'Π(5) = -48'  => [5, -48],
                'Π(-5) = 112' => [-5, 112],
            ];
        }

        /**
         * @test         sum
         * @dataProvider dataProviderForSum
         *
         * @param int $x
         * @param int $expectedSum
         */
        public function testSum(int $x, int $expectedSum)
        {
            // Given
            // f(x) = x⁴ + 8x³ -13x² -92x + 96
            $f = fn($x) => (($x ** 4 + 8 * $x ** 3) - (13 * $x ** 2) - (92
                        * $x))
                + 96;

            // g(x) = x³ - 12x² + 72x + 27
            $g = fn($x) => (($x ** 3) - (12 * $x ** 2)) + (72 * $x) + 27;

            // Σ(x) = f(x) + g(x) = x⁴ + 9x³ -25x² -20x + 123
            $adder = Arithmetic::add($f, $g);

            // When
            $sum = $adder($x);

            // Then
            $this->assertEquals($expectedSum, $sum);
        }

        /**
         * @test         multiply
         * @dataProvider dataProviderForMultiply
         *
         * @param int $x
         * @param int $expectedProduct
         */
        public function testMultiply(int $x, int $expectedProduct)
        {
            // f(x) = x² + 8x - 12
            $f = fn($x) => (($x ** 2) + (8 * $x)) - 12;

            // g(x) = x - 9
            $g = fn($x) => $x - 9;

            // Π(x) = f(x) * g(x) = x³ - x² -84x + 108
            $multiplier = Arithmetic::multiply($f, $g);

            // When
            $product = $multiplier($x);

            // Then
            $this->assertEquals($expectedProduct, $product);
        }

        /**
         * @test         Multiple sums
         * @dataProvider dataProviderForMultipleSums
         *
         * @param int $x
         * @param int $expectedSum
         */
        public function testMultipleSums(int $x, int $expectedSum)
        {
            // Given
            // f(x) = 8x³ - 13x² -92x + 96
            $f = fn($x) => ((8 * $x ** 3) - (13 * $x ** 2) - (92 * $x)) + 96;

            // Σ(x) = f(x) + f(x) + f(x) + f(x) + f(x) = 5*f(x) = 40x³ - 65x² -460x + 480
            $adder = Arithmetic::add($f, $f, $f, $f, $f);

            // When
            $sum = $adder($x);

            // Then
            $this->assertEquals($expectedSum, $sum);
        }

        /**
         * @test         Multiple products
         * @dataProvider dataProviderForMultipleProducts
         *
         * @param int $x
         * @param int $expectedSum
         */
        public function testMultipleProducts(int $x, int $expectedSum)
        {
            // f(x) = x - 9
            $f = fn($x) => $x - 9;

            // g(x) = x + 2
            $g = fn($x) => $x + 2;

            // h(x) = x
            $h = fn($x) => $x;

            // Π(x) = f(x) * g(x) * h(x) = x³ - 7x² -18x
            $multiplier = Arithmetic::multiply($f, $g, $h);

            // When
            $product = $multiplier($x);

            // Then
            $this->assertEquals($expectedSum, $product);
        }

        /**
         * @test         Nested arithmetic
         * @dataProvider dataProviderForNestedArithmetic
         *
         * @param int $x
         * @param int $expected
         */
        public function testNestedArithmetic(int $x, int $expected)
        {
            // f(x) = x - 9
            $f = fn($x) => $x - 9;

            // g(x) = x + 2
            $g = fn($x) => $x + 2;

            // h(x) = x
            $h = fn($x) => $x;

            // Π(x) = $f(x) * ( g(x) + h(x) ) = (x - 9) * (2x + 2) = 2x² - 16x - 18
            $multiplier = Arithmetic::multiply($f, Arithmetic::add($g, $h));

            // When
            $product = $multiplier($x);

            // Then
            $this->assertEquals($expected, $product);
        }
    }
