<?php

    namespace MathPHP\Tests\NumericalAnalysis\RootFinding;

    use JetBrains\PhpStorm\ArrayShape;
    use MathPHP\Expression\Polynomial;
    use MathPHP\NumericalAnalysis\RootFinding\NewtonsMethod;
    use PHPUnit\Framework\TestCase;

    use function cos;
    use function sqrt;

    class NewtonsMethodTest extends TestCase {
        /**
         * @return array (args, expected)
         */
        #[ArrayShape([
            'solving for f(x) = 0 where x is -4' => "array",
            'solving for f(x) = 0 where x is -8' => "array",
            'solving for f(x) = 0 where x is 3'  => "array",
            'When solving f(x) = 0 where x is 1' => "array"
        ])] public static function dataProviderForPolynomial(): array
        {
            return [
                'solving for f(x) = 0 where x is -4' => [[-4.1], -4],
                'solving for f(x) = 0 where x is -8' => [[-8.4], -8],
                'solving for f(x) = 0 where x is 3'  => [[3.5], 3],
                'When solving f(x) = 0 where x is 1' => [[-.3], 1],
            ];
        }

        /**
         * @test         Solve f(x) = x⁴ + 8x³ -13x² -92x + 96
         *         Polynomial has 4 roots: 3, 1, -8 and -4
         *         Uses \Closure object
         * @dataProvider dataProviderForPolynomial
         *
         * @param float[] $args
         * @param int     $expected
         *
         * @throws       \Exception
         */
        public function testSolvePolynomialWithFourRootsUsingClosure(
            array $args,
            int $expected
        ) {
            // Given
            $func = fn($x) => ($x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - (92 * $x)) + 96;

            // And
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When solving for f(x) = 0 where x is $expected
            $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);

            // Then
            $this->assertEqualsWithDelta($expected, $x, $tol);
        }

        /**
         * @test         Solve f(x) = x⁴ + 8x³ -13x² -92x + 96
         *         Polynomial has 4 roots: 3, 1, -8 and -4
         *         Uses Polynomial object
         * @dataProvider dataProviderForPolynomial
         *
         * @param float[] $args
         * @param int     $expected
         *
         * @throws       \Exception
         */
        public function testSolvePolynomialWithFourRootsUsingPolynomial(
            array $args,
            int $expected
        ) {
            // Given
            $polynomial = new Polynomial([1, 8, -13, -92, 96]);

            // And
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When solving for f(x) = 0 where x is $expected
            $x = NewtonsMethod::solve($polynomial, $args, $target, $tol,
                $position);

            // Then
            $this->assertEqualsWithDelta($expected, $x, $tol);
        }

        /**
         * @test   Solve f(x) = x³ - x + 1
         *         Polynomial has a root of approximately -1.324717
         * @throws \Exception
         */
        public function testXCubedSubtractXPlusOne()
        {
            // Given
            $func = fn($x) => ($x ** 3 - $x) + 1;

            // And
            $expected = -1.324717;
            $args = [-1];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $root = NewtonsMethod::solve($func, $args, $target, $tol,
                $position);

            // Then
            $this->assertEqualsWithDelta($expected, $root, $tol);
        }

        /**
         * @test   Solve f(x) = x² - 5
         *         Polynomial has a root of √5
         * @throws \Exception
         */
        public function testXSquaredSubtractFive()
        {
            // Given
            $func = fn($x) => ($x ** 2) - 5;

            // And
            $expected = sqrt(5);
            $args = [2];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $root = NewtonsMethod::solve($func, $args, $target, $tol,
                $position);

            // Then
            $this->assertEqualsWithDelta($expected, $root, $tol);
        }

        /**
         * @test   Solve \cos(x) - 2x
         *         Has a root of approximately 0.450183
         * @throws \Exception
         */
        public function testCosXSubtractTwoX()
        {
            // Given
            $func = fn($x) => cos($x) - (2 * $x);

            // And
            $expected = 0.450183;
            $args = [0];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $root = NewtonsMethod::solve($func, $args, $target, $tol,
                $position);

            // Then
            $this->assertEqualsWithDelta($expected, $root, $tol);
        }

        /**
         * @test   Solve \cos(x) = x
         *         Has a root of approximately 0.7390851332
         * @throws \Exception
         */
        public function testCosXEqualsX()
        {
            // Given
            $func = fn($x) => cos($x);

            // And
            $x = 0.7390851332;
            $args = [0.6];
            $target = $x;
            $position = 0;
            $tol = 0.00001;

            // When
            $root = NewtonsMethod::solve($func, $args, $target, $tol,
                $position);

            // Then
            $this->assertEqualsWithDelta($x, $root, $tol);
        }

        /**
         * @test   Solve with negative tolerance
         * @throws \Exception
         */
        public function testNewtonsMethodExceptionNegativeTolerance()
        {
            // Given
            $func = fn($x) => ($x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x) + 96;

            // And
            $args = [-4.1];
            $target = 0;
            $position = 0;
            $tol = -0.00001;

            // Then
            $this->expectException('\Exception');

            // When
            $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        }

        /**
         * @test   Solve with near zero slope
         * @throws \Exception
         */
        public function testNewtonsMethodNearZeroSlopeNAN()
        {
            // Given
            $func = fn($x) => $x / $x;

            // And
            $args = [0.1];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);

            // Then
            $this->assertNan($x);
        }

        /**
         * @test   Solve with no real solutions
         * @throws \Exception
         */
        public function testNewtonsMethodNoRealSolutionsNAN()
        {
            // Given
            $func = fn($x) => ($x ** 2) + (3 * $x) + 3;

            // And
            $args = [0.1];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);

            // Then
            $this->assertNan($x);
        }

        /**
         * @test   Solve f(x) = ³√x for ³√x = 0
         *         Has no solution
         * @throws \Exception
         */
        public function testNoSolutionCubeRootX()
        {
            // Given
            $func = fn($x) => $x ** (1 / 3);

            // And
            $args = [1];
            $target = 0;
            $position = 0;
            $tol = 0.00001;

            // When
            $root = NewtonsMethod::solve($func, $args, $target, $tol,
                $position);

            // Then
            $this->assertNan($root);
        }
    }
