<?php

    namespace MathPHP\Probability\Distribution\Multivariate;

    use MathPHP\Exception;
    use MathPHP\Functions\Special;
    use MathPHP\Functions\Support;

    use function array_map;
    use function array_product;
    use function count;

    /**
     * Dirichlet distribution
     * https://en.wikipedia.org/wiki/Dirichlet_distribution
     */
    class Dirichlet {
        /**
         * Distribution parameter bounds limits
         * α ∈ (0,∞)
         *
         * @var array{"α": string}
         */
        public const PARAMETER_LIMITS
            = [
                'α' => '(0,∞)',
            ];

        /**
         * Distribution parameter bounds limits
         * x ∈ (0,1)
         *
         * @var array{x: string}
         */
        public const SUPPORT_LIMITS
            = [
                'x' => '(0,1)',
            ];

        /** @var float[] $αs */
        protected array $αs;

        /**
         * Constructor
         *
         * @param float[] $αs
         *
         * @throws \MathPHP\Exception\BadDataException
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function __construct(array $αs)
        {
            $n = count($αs);
            for ($i = 0; $i < $n; $i++)
                Support::checkLimits(self::PARAMETER_LIMITS, ['α' => $αs[$i]]);
            $this->αs = $αs;
        }

        /**
         * Probability density function
         *
         *        1    K   αᵢ-1
         * pdf = ----  ∏ xᵢ
         *       B(α) ⁱ⁼ⁱ
         *
         * where B(α) is the multivariate Beta function
         *
         * @param float[] $xs
         *
         * @return float
         *
         * @throws \MathPHP\Exception\BadDataException if xs and αs don't have the same number of elements
         * @throws \MathPHP\Exception\BadParameterException
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function pdf(array $xs): float
        {
            if (count($xs) !== count($this->αs))
                throw new Exception\BadDataException('xs and αs must have the same number of elements');

            $n = count($xs);
            for ($i = 0; $i < $n; $i++)
                Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $xs[$i]]);

            /*
             *  K   αᵢ-1
             *  ∏ xᵢ
             * ⁱ⁼ⁱ
             */
            $∏xᵢ = array_product(
                array_map(
                    fn($xᵢ, $αᵢ) => $xᵢ ** ($αᵢ - 1),
                    $xs,
                    $this->αs
                )
            );

            try
            {
                $B⟮α⟯ = Special::multivariateBeta($this->αs);
            } catch (Exception\OutOfBoundsException $e)
            {
            }

            return $∏xᵢ / $B⟮α⟯;
        }

        public function pdfArraysNotSameLengthException()
        {
        }
    }
