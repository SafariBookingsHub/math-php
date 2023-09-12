<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Support;

    use function exp;
    use function log;

    use const INF;
    use const M_PI;

    /**
     * Logistic distribution
     * https://en.wikipedia.org/wiki/Logistic_distribution
     */
    class Logistic extends Continuous {
        /**
         * Distribution parameter bounds limits
         * μ ∈ (-∞,∞)
         * s ∈ (0,∞)
         *
         * @var array{"μ": string, "s": string}
         */
        public const PARAMETER_LIMITS
            = [
                'μ' => '(-∞,∞)',
                's' => '(0,∞)',
            ];

        /**
         * Distribution support bounds limits
         * x ∈ (-∞,∞)
         *
         * @var array{x: string}
         */
        public final const SUPPORT_LIMITS
            = [
                'x' => '(-∞,∞)',
            ];

        /** @var float Location Parameter */
        protected float $μ;

        /** @var float Scale Parameter */
        protected float $s;

        /**
         * Constructor
         *
         * @param float $μ shape parameter
         * @param float $s shape parameter s > 0
         */
        public function __construct(float $μ, float $s)
        {
            parent::__construct($μ, $s);
        }

        public static function inverseOfCdf()
        {
        }

        /**
         * Probability density function
         *
         *                     /  x - μ \
         *                 exp| - -----  |
         *                     \    s   /
         * f(x; μ, s) = -----------------------
         *                /        /  x - μ \ \ ²
         *              s| 1 + exp| - -----  | |
         *                \        \    s   / /
         *
         * @param float $x
         *
         * @return float
         */
        public function pdf(float $x): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }

            $μ = $this->μ;
            $s = $this->s;

            $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);

            return $ℯ＾⁻⁽x⁻μ⁾／s / ($s * (1 + $ℯ＾⁻⁽x⁻μ⁾／s) ** 2);
        }

        /**
         * Cumulative distribution function
         * From -∞ to x (lower CDF)
         *
         *                      1
         * f(x; μ, s) = -------------------
         *                      /  x - μ \
         *              1 + exp| - -----  |
         *                      \    s   /
         *
         * @param float $x
         *
         * @return float
         */
        public function cdf(float $x): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }

            $μ = $this->μ;
            $s = $this->s;

            $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);

            return 1 / (1 + $ℯ＾⁻⁽x⁻μ⁾／s);
        }

        /**
         * Inverse CDF (quantile function)
         *
         *                     /   p   \
         * Q(p;μ,s) = μ + s ln|  -----  |
         *                     \ 1 - p /
         *
         * @param float $p
         *
         * @return float
         */
        public function inverse(float $p): float
        {
            try
            {
                Support::checkLimits(['p' => '[0,1]'], ['p' => $p]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
            $μ = $this->μ;
            $s = $this->s;

            if ($p == 1)
            {
                {
                    return INF;
                }
            }

            return $μ + ($s * log($p / (1 - $p)));
        }

        /**
         * Mean of the distribution
         *
         * μ = μ
         *
         * @return float μ
         */
        public function mean(): float
        {
            return $this->μ;
        }

        /**
         * Median of the distribution
         *
         * median = μ
         *
         * @return float μ
         */
        public function median(): float
        {
            return $this->μ;
        }

        /**
         * Mode of the distribution
         *
         * mode = μ
         *
         * @return float μ
         */
        public function mode(): float
        {
            return $this->μ;
        }

        /**
         * Variance of the distribution
         *
         *          s²π²
         * var[X] = ----
         *           3
         *
         * @return float
         */
        public function variance(): float
        {
            $s² = $this->s ** 2;
            $π² = M_PI ** 2;

            return ($s² * $π²) / 3;
        }

        public function rand(): float|int
        {
        }
    }
