<?php

    namespace MathPHP\Tests\LinearAlgebra\Vector;

    use MathPHP\Exception\BadDataException;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    class VectorNormsTest extends TestCase {
        public static function dataProviderForL1Norm(): array
        {
            return [
                [[1, 2, 3], 6],
                [[-7, 5, 5], 17],
            ];
        }

        public static function dataProviderForL2Norm(): array
        {
            return [
                [[1, 2, 3], 3.7416573867739413],
                [[7, 5, 5], 9.9498743710662],
                [[3, 3, 3], 5.196152422706632],
                [[2, 2, 2], 3.4641016151377544],
                [[1, 1, 1], 1.7320508075688772],
                [[0, 0, 0], 0],
                [[1, 0, 0], 1],
                [[1, 1, 0], 1.4142135623730951],
                [[-1, 1, 0], 1.4142135623730951],
            ];
        }

        public static function dataProviderForPNorm(): array
        {
            return [
                [[1, 2, 3], 2, 3.74165738677],
                [[1, 2, 3], 3, 3.30192724889],
                [[-1, 2, -3], 1, 6],
                [[-1, 2, -3], 3, 3.30192724889],
            ];
        }

        public static function dataProviderForMaxNorm(): array
        {
            return [
                [[1, 2, 3], 3],
                [[7, -5, 5], 7],
                [[-3, -7, 6, 3], 7],
            ];
        }

        /**
         * @test         l1 norm
         * @dataProvider dataProviderForL1Norm
         */
        public function testL1Norm(array $A, $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }

            // When
            $l₁norm = $A->l1Norm();

            // Then
            $this->assertEqualsWithDelta($expected, $l₁norm, 0.0001);
        }

        /**
         * @test         l2 norm
         * @dataProvider dataProviderForL2Norm
         */
        public function testL2Norm(array $A, $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }

            // When
            $l²norm = $A->l2Norm();

            // Then
            $this->assertEqualsWithDelta($expected, $l²norm, 0.0001);
        }

        /**
         * @test         p norm
         * @dataProvider dataProviderForPNorm
         */
        public function testPNorm(array $A, $p, $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }

            // When
            $pnorm = $A->pNorm($p);

            // Then
            $this->assertEqualsWithDelta($expected, $pnorm, 0.0001);
        }

        /**
         * @test         max norm
         * @dataProvider dataProviderForMaxNorm
         */
        public function testMaxNorm(array $A, $expected)
        {
            // Given
            try
            {
                $A = new Vector(A: $A);
            } catch (BadDataException $e)
            {
            }

            // When
            $maxnorm = $A->maxNorm();

            // Then
            $this->assertEqualsWithDelta($expected, $maxnorm, 0.0001);
        }
    }
