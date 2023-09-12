<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    use const INF;

    /**
     * Dirac Delta Function
     * https://en.wikipedia.org/wiki/Dirac_delta_function
     */
    class DiracDelta extends Continuous {
        /**
         * Distribution parameter bounds limits
         *
         * @var array{}
         */
        public const PARAMETER_LIMITS = [];

        /**
         * Distribution support bounds limits
         * x  ∈ (-∞,∞)
         *
         * @var array{x: string}
         */
        public const SUPPORT_LIMITS
            = [
                'x' => '(-∞,∞)',
            ];

        /**
         * Constructor
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Mode of the distribution
         *
         * @return int
         */
        public static function mode(): int
        {
            return 0;
        }

        /**
         * Probability density function
         *
         *
         *          /‾
         *         |  +∞,   x = 0
         * δ(x) = <
         *         |  0,    x ≠ 0
         *          \_
         *
         *
         * @param float $x
         *
         * @return float probability
         */
        public function pdf(float $x): float
        {
            if ($x == 0)
                return INF;

            return 0;
        }

        /**
         * Cumulative distribution function
         * https://en.wikipedia.org/wiki/Heaviside_step_function
         *
         *  |\+∞
         *  |   δ(x) dx = 1
         * \|-∞
         *
         * @param float $x
         *
         * @return int
         * @todo how to handle x = 0, depending on context, some say CDF=.5 @ x=0
         *
         */
        public function cdf(float $x): int
        {
            if ($x >= 0)
                return 1;

            return 0;
        }

        /**
         * The inverse of the CDF function
         *
         * @param float $p
         *
         * @return int
         */
        public function inverse(float $p): int
        {
            return 0;
        }

        /**
         * Mean of the distribution
         *
         * @return int
         */
        public function mean(): int
        {
            return 0;
        }

        /**
         * Median of the distribution
         *
         * @return int
         */
        public function median(): int
        {
            return 0;
        }

        public function rand()
        {
        }
    }
