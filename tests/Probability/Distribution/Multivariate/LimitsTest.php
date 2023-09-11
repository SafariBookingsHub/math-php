<?php

    namespace MathPHP\Tests\Probability\Distribution\Multivariate;

    use MathPHP\Probability\Distribution\Multivariate;
    use PHPUnit\Framework\TestCase;

    class LimitsTest extends TestCase {
        /**
         * @test Limits constant is correct format
         */
        public function testDirichletParameterLimits()
        {
            $this->limitTest(Multivariate\Dirichlet::PARAMETER_LIMITS);
        }

        /**
         * Limits should look like:
         *  (a,b)
         *  [a,b)
         *  (a,b]
         *  [a,b]
         */
        private function limitTest(array $limits)
        {
            foreach ($limits as $parameter => $limit)
            {
                $this->assertRegExp('/^ ([[(]) (.+) , (.+?) ([])]) $/x',
                    $limit);
            }
        }

        /**
         * @test Limits constant is correct format
         */
        public function testDirichletSupportLimits()
        {
            $this->limitTest(Multivariate\Dirichlet::SUPPORT_LIMITS);
        }

        /**
         * @test Limits constant is correct format
         */
        public function testHypergeometricParameterLimits()
        {
            $this->limitTest(Multivariate\Hypergeometric::PARAMETER_LIMITS);
        }
    }
