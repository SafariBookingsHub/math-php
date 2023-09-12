<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    /**
     * Standard normal distribution
     * The simplest case of a normal distribution.
     * This is a special case when μ = 0 and σ = 1,
     */
    class StandardNormal extends Normal {
        /**
         * Mean is always 0
         *
         * @var int
         */
        protected final const μ = 0;

        /**
         * Standard deviation is always 1
         *
         * @var int
         */
        protected final const σ = 1;

        /**
         * Distribution parameter bounds limits
         * μ ∈ [0,0]
         * σ ∈ [1,1]
         *
         * @var array{"μ": string, "σ": string}
         */
        public const PARAMETER_LIMITS
            = [
                'μ' => '[-0,0]',
                'σ' => '[1,1]',
            ];

        /**
         * Distribution support bounds limits
         * z ∈ (-∞,∞)
         *
         * @var array{z: string}
         */
        public const SUPPORT_LIMITS
            = [
                'z' => '(-∞,∞)',
            ];

        /**
         * StandardNormal constructor
         */
        public function __construct()
        {
            parent::__construct(self::μ, self::σ);
        }

        public static function cdfEqualsNormalWithMeanZeroAndStandardDeviationOne(
        )
        {
        }

        public static function pdfEqualsNormalWithMeanZeroAndStandardDeviationOne(
        )
        {
        }

        public function inverse(float $p): float
        {
        }

        public function variance(): float
        {
        }

        public function mode(): float
        {
        }

        public function mean(): float
        {
        }

        public function cdf(float $x): float
        {
        }

        public function pdf(float $x): float
        {
        }
    }
