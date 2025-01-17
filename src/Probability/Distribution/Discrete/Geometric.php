<?php

    namespace MathPHP\Probability\Distribution\Discrete;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Support;

    use function ceil;
    use function log;

    /**
     * Geometric distribution
     *
     * https://en.wikipedia.org/wiki/Geometric_distribution
     */
    class Geometric extends Discrete {
        /**
         * Distribution parameter bounds limits
         * p ∈ (0,1]
         *
         * @var array{"p": string}
         */
        public const PARAMETER_LIMITS
            = [
                'p' => '(0,1]',
            ];

        /**
         * Distribution parameter bounds limits
         * k ∈ [1,∞)
         *
         * @var array{"k": string}
         */
        public final const SUPPORT_LIMITS
            = [
                'k' => '[1,∞)',
            ];

        /** @var float success probability  0 < p ≤ 1 */
        protected float $p;

        /**
         * Constructor
         *
         * @param float $p success probability  0 < p ≤ 1
         */
        public function __construct(float $p)
        {
            parent::__construct($p);
        }

        /**
         * Mode of the distribution
         *
         * mode = 0
         *
         * @return int
         */
        public static function mode(): int
        {
            return 0;
        }

        /**
         * Probability mass function
         *
         * The probability distribution of the number Y = X − 1 of failures
         * before the first success, supported on the set { 0, 1, 2, 3, ... }
         *
         * k failures where k ∈ {0, 1, 2, 3, ...}
         *
         * pmf = (1 - p)ᵏp
         *
         * @param int $k number of trials     k ≥ 1
         *
         * @return float
         */
        public function pmf(int $k): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            $p = $this->p;

            $⟮1 − p⟯ᵏ = (1 - $p) ** $k;

            return $⟮1 − p⟯ᵏ * $p;
        }

        /**
         * Cumulative distribution function (lower cumulative)
         *
         * The probability distribution of the number Y = X − 1 of failures
         * before the first success, supported on the set { 0, 1, 2, 3, ... }
         *
         * k failures where k ∈ {0, 1, 2, 3, ...}
         *
         * pmf = 1 - (1 - p)ᵏ⁺¹
         *
         * @param int $k number of trials     k ≥ 0
         *
         * @return float
         */
        public function cdf(int $k): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            $p = $this->p;

            $⟮1 − p⟯ᵏ⁺¹ = (1 - $p) ** ($k + 1);

            return 1 - $⟮1 − p⟯ᵏ⁺¹;
        }

        /**
         * Mean of the distribution
         *
         *     1 - p
         * μ = -----
         *       p
         *
         * @return float
         */
        public function mean(): float
        {
            return (1 - $this->p) / $this->p;
        }

        /**
         * Median of the distribution
         *
         *           _           _
         *          |     -1      |
         * median = | ----------- | - 1
         *          | log₂(1 - p) |
         *
         * @return float
         */
        public function median(): float
        {
            $log₂⟮1 − p⟯ = log(1 - $this->p, 2);

            return ceil(-1 / $log₂⟮1 − p⟯) - 1;
        }

        /**
         * Variance of the distribution
         *
         *      1 - p
         * σ² = -----
         *        p²
         *
         * @return float
         */
        public function variance(): float
        {
            return (1 - $this->p) / $this->p ** 2;
        }
    }
